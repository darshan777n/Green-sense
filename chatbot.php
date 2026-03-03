<button id="chatbotToggle" class="chatbot-toggle" type="button" aria-label="Open chatbot">
  <svg class="chatbot-icon" viewBox="0 0 24 24" aria-hidden="true">
    <path d="M4 5h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H9l-5 4v-4H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" fill="currentColor"></path>
  </svg>
</button>

<section id="chatbotWidget" class="chatbot-widget" aria-live="polite">
  <div class="chatbot-header">
    <strong>Green Sense Assistant</strong>
    <button id="chatbotClose" class="chatbot-close" type="button" aria-label="Close chatbot">x</button>
  </div>
  <div id="chatbotMessages" class="chatbot-messages">
    <p class="chatbot-msg bot">Hi! I can help with products, cart, login, and payment.</p>
  </div>
  <form id="chatbotForm" class="chatbot-form">
    <input id="chatbotInput" type="text" placeholder="Ask something..." maxlength="200" required />
    <button class="btn btn-detail btn-inline" type="submit">Send</button>
  </form>
</section>

<script>
  (function () {
    const toggle = document.getElementById('chatbotToggle');
    const widget = document.getElementById('chatbotWidget');
    const closeBtn = document.getElementById('chatbotClose');
    const form = document.getElementById('chatbotForm');
    const input = document.getElementById('chatbotInput');
    const messages = document.getElementById('chatbotMessages');

    if (!toggle || !widget || !closeBtn || !form || !input || !messages) {
      return;
    }

    function addMessage(text, type) {
      const p = document.createElement('p');
      p.className = 'chatbot-msg ' + type;
      p.textContent = text;
      messages.appendChild(p);
      messages.scrollTop = messages.scrollHeight;
    }

    function getReply(query) {
      const q = query.toLowerCase();
      if (q.includes('coffee') || q.includes('tea') || q.includes('product')) {
        return 'You can browse all items on the Products page and open View More Details for each product.';
      }
      if (q.includes('cart') || q.includes('add')) {
        return 'Use Add to Cart from product cards, then open Cart to review items, remove products, or buy now.';
      }
      if (q.includes('login') || q.includes('register') || q.includes('account')) {
        return 'Go to Login/Register from the header, create account, then sign in with email and password.';
      }
      if (q.includes('payment') || q.includes('buy')) {
        return 'Buy Now opens the temporary payment page where you can choose Card, UPI, or Cash on Delivery.';
      }
      if (q.includes('hello') || q.includes('hi')) {
        return 'Hello! Ask me about products, cart, login, or payment.';
      }
      return 'I can help with products, cart, login, and payment. Please try one of those topics.';
    }

    toggle.addEventListener('click', () => {
      widget.classList.add('open');
      input.focus();
    });

    closeBtn.addEventListener('click', () => {
      widget.classList.remove('open');
    });

    form.addEventListener('submit', (event) => {
      event.preventDefault();
      const text = input.value.trim();
      if (!text) {
        return;
      }
      addMessage(text, 'user');
      addMessage(getReply(text), 'bot');
      input.value = '';
    });
  })();
</script>
