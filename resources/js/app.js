import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('carousel', (config = {}) => ({
    index: 0,
    count: config.count ?? 0,
    perView: config.perView ?? 1,
    autoplay: config.autoplay ?? false,
    interval: config.interval ?? 4000,
    timer: null,

    get maxIndex() {
        return Math.max(0, this.count - this.perView);
    },

    init() {
        if (this.autoplay && this.count > this.perView) {
            this.play();
        }
    },

    play() {
        this.stop();
        this.timer = setInterval(() => this.next(), this.interval);
    },

    stop() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },

    next() {
        this.index = this.index >= this.maxIndex ? 0 : this.index + 1;
    },

    prev() {
        this.index = this.index <= 0 ? this.maxIndex : this.index - 1;
    },

    goTo(i) {
        this.index = Math.min(i, this.maxIndex);
    },

    get offset() {
        return this.perView > 0 ? -(this.index * (100 / this.perView)) : 0;
    },
}));

Alpine.data('navDropdown', () => ({
    open: false,
    closeTimer: null,

    show() {
        if (this.closeTimer) {
            clearTimeout(this.closeTimer);
            this.closeTimer = null;
        }
        this.open = true;
    },

    hide() {
        this.closeTimer = setTimeout(() => {
            this.open = false;
            this.closeTimer = null;
        }, 150);
    },

    toggle() {
        this.open = !this.open;
    },
}));

/**
 * Chatbot display preferences, persisted per browser so the widget stays
 * out of the way (compact or hidden) across page loads.
 */
const chatbotPreferences = {
    read(key) {
        try {
            return window.localStorage.getItem(`eduroute.chatbot.${key}`) === '1';
        } catch {
            return false;
        }
    },

    write(key, enabled) {
        try {
            const storageKey = `eduroute.chatbot.${key}`;

            if (enabled) {
                window.localStorage.setItem(storageKey, '1');
            } else {
                window.localStorage.removeItem(storageKey);
            }
        } catch {
            // Storage unavailable (private mode): keep the in-memory state only.
        }
    },
};

Alpine.data('chatbot', (config = {}) => ({
    open: false,
    compact: false,
    hidden: false,
    teaserDismissed: false,
    loading: false,
    hovered: false,
    input: '',
    error: null,
    messages: [],
    suggestions: config.suggestions ?? [],
    iconPaths: config.icons ?? {},
    endpoint: config.endpoint ?? '',
    greeting: config.greeting ?? 'Halo! Ada yang bisa saya bantu?',
    unavailableMessage: config.unavailableMessage ?? '',
    messageId: 0,

    init() {
        this.messages = [this.createMessage('assistant', this.greeting)];
        this.compact = chatbotPreferences.read('compact');
        this.hidden = chatbotPreferences.read('hidden');
        this.teaserDismissed = chatbotPreferences.read('teaser-dismissed');
    },

    get showSuggestions() {
        return ! this.loading && this.suggestions.length > 0 && this.messages.length <= 1;
    },

    get showLauncher() {
        return ! this.hidden;
    },

    get showTeaser() {
        return ! this.open && ! this.hidden && ! this.compact && ! this.teaserDismissed;
    },

    get launcherSizeClasses() {
        return this.compact
            ? 'h-12 w-12 justify-center p-0'
            : 'min-h-14 gap-3 py-2.5 pl-3 pr-6';
    },

    get history() {
        return this.messages
            .slice(-10)
            .map((message) => ({
                role: message.role,
                content: message.content.slice(0, 2000),
            }));
    },

    createMessage(role, content) {
        return {
            id: ++this.messageId,
            role,
            content,
            time: new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit' }).format(new Date()),
        };
    },

    toggle() {
        this.open = ! this.open;

        if (this.open) {
            this.$nextTick(() => this.$refs.chatInput?.focus());
        }
    },

    close() {
        this.open = false;
        this.$nextTick(() => this.$refs.chatToggle?.focus());
    },

    /** Collapse the panel into a small circular launcher button. */
    minimize() {
        this.open = false;
        this.compact = true;
        this.hidden = false;
        chatbotPreferences.write('compact', true);
        chatbotPreferences.write('hidden', false);
        this.$nextTick(() => this.$refs.chatToggle?.focus());
    },

    /** Collapse the panel into a small circular launcher button, or undo it. */
    toggleCompact() {
        if (this.compact) {
            this.expand();
        } else {
            this.minimize();
        }
    },

    /** Restore the full launcher pill from compact mode. */
    expand() {
        this.compact = false;
        chatbotPreferences.write('compact', false);
    },

    /** Hide the whole widget, leaving a small restore handle. */
    hide() {
        this.open = false;
        this.compact = false;
        this.hidden = true;
        chatbotPreferences.write('compact', false);
        chatbotPreferences.write('hidden', true);
    },

    /** Bring the widget back from the hidden state. */
    restore() {
        this.hidden = false;
        chatbotPreferences.write('hidden', false);
    },

    /** Permanently hide the greeting teaser bubble. */
    dismissTeaser() {
        this.teaserDismissed = true;
        chatbotPreferences.write('teaser-dismissed', true);
    },

    reset() {
        this.error = null;
        this.input = '';
        this.messages = [this.createMessage('assistant', this.greeting)];
        this.$nextTick(() => this.$refs.chatInput?.focus());
    },

    askSuggestion(question) {
        this.input = question;
        this.send();
    },

    autoGrow(event) {
        const element = event.target;

        element.style.height = 'auto';
        element.style.height = `${Math.min(element.scrollHeight, 112)}px`;
    },

    async send() {
        const question = this.input.trim();

        if (question === '' || this.loading) {
            return;
        }

        this.error = null;
        this.input = '';

        if (this.$refs.chatInput) {
            this.$refs.chatInput.style.height = 'auto';
        }

        this.messages.push(this.createMessage('user', question));
        this.loading = true;
        this.scrollToLatest();

        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                body: JSON.stringify({
                    message: question,
                    history: this.history.slice(0, -1),
                }),
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(data.error ?? 'Maaf, terjadi kendala. Silakan coba lagi.');
            }

            this.messages.push(this.createMessage('assistant', data.answer));
        } catch (error) {
            this.error = error.message;
            this.messages.push(this.createMessage('assistant', 'Maaf, saya belum bisa menjawab saat ini. Silakan coba lagi nanti.'));
        } finally {
            this.loading = false;
            this.scrollToLatest();
        }
    },

    scrollToLatest() {
        this.$nextTick(() => {
            const container = this.$refs.chatLog;

            if (container) {
                const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                container.scrollTo({ top: container.scrollHeight, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
            }
        });
    },
}));

let scrollRevealObserver = null;

/**
 * Scroll reveal animations.
 * Adds `is-revealed` to `[data-reveal]` elements once they enter the viewport.
 */
export function initScrollReveal(container = document, reset = false) {
    const elements = container.querySelectorAll('[data-reveal]');

    if (elements.length === 0) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion || ! ('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-revealed'));

        return;
    }

    document.documentElement.dataset.revealInitialized = 'true';

    if (!scrollRevealObserver) {
        scrollRevealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-revealed');
                scrollRevealObserver.unobserve(entry.target);
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -40px 0px',
        });
    }

    elements.forEach((element) => {
        if (reset) {
            element.classList.remove('is-revealed');
        }

        if (!element.classList.contains('is-revealed')) {
            scrollRevealObserver.observe(element);
        }
    });
}

window.initScrollReveal = initScrollReveal;

Alpine.start();

initScrollReveal();

