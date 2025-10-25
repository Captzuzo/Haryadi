/* // resources/assets/js/welcome.js */

class ThemeManager {
  constructor() {
    this.themeToggle = document.getElementById("themeToggle");
    this.themeIcon = document.getElementById("themeIcon");
    this.body = document.body;
    this.init();
  }

  init() {
    this.loadTheme();
    this.bindEvents();
    this.initAnimations();
  }

  loadTheme() {
    const savedTheme = localStorage.getItem("theme");
    const prefersDark = window.matchMedia(
      "(prefers-color-scheme: dark)"
    ).matches;

    if (savedTheme === "dark" || (!savedTheme && prefersDark)) {
      this.setDarkTheme();
    } else {
      this.setLightTheme();
    }
  }

  setDarkTheme() {
    this.body.setAttribute("data-theme", "dark");
    this.themeIcon.textContent = "☀️";
    localStorage.setItem("theme", "dark");
  }

  setLightTheme() {
    this.body.setAttribute("data-theme", "light");
    this.themeIcon.textContent = "🌙";
    localStorage.setItem("theme", "light");
  }

  toggleTheme() {
    const currentTheme = this.body.getAttribute("data-theme");

    if (currentTheme === "light") {
      this.setDarkTheme();
    } else {
      this.setLightTheme();
    }

    this.createRippleEffect(event);
  }

  createRippleEffect(event) {
    const button = event.currentTarget;
    const ripple = document.createElement("span");
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.4);
            transform: scale(0);
            animation: ripple 0.6s linear;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            pointer-events: none;
        `;

    button.appendChild(ripple);

    setTimeout(() => {
      ripple.remove();
    }, 600);
  }

  bindEvents() {
    this.themeToggle.addEventListener("click", (e) => this.toggleTheme(e));

    // Listen for system theme changes
    window
      .matchMedia("(prefers-color-scheme: dark)")
      .addEventListener("change", (e) => {
        if (!localStorage.getItem("theme")) {
          if (e.matches) {
            this.setDarkTheme();
          } else {
            this.setLightTheme();
          }
        }
      });
  }

  initAnimations() {
    this.animateOnScroll();
    this.addHoverEffects();
  }

  animateOnScroll() {
    const animateElements = document.querySelectorAll(
      ".framework-info, .quick-links, .feature-card, .docs-section"
    );

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      }
    );

    animateElements.forEach((element) => {
      element.style.opacity = "0";
      element.style.transform = "translateY(20px)";
      element.style.transition = "all 0.6s ease";
      observer.observe(element);
    });
  }

  addHoverEffects() {
    const linkCards = document.querySelectorAll(".link-card");

    linkCards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-5px) scale(1.02)";
      });

      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0) scale(1)";
      });
    });

    // Add click effects to buttons
    const buttons = document.querySelectorAll(".btn");
    buttons.forEach((button) => {
      button.addEventListener("click", function (e) {
        const ripple = document.createElement("span");
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.4);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    pointer-events: none;
                `;

        this.appendChild(ripple);

        setTimeout(() => {
          ripple.remove();
        }, 600);
      });
    });
  }
}

/* Initialize when DOM is loaded */
document.addEventListener("DOMContentLoaded", function () {
  new ThemeManager();

  // Additional initialization can go here
  console.log("Haryadi Framework Welcome Page Loaded");

  // Add loading state
  const body = document.body;
  body.style.opacity = "0";
  body.style.transition = "opacity 0.3s ease";

  setTimeout(() => {
    body.style.opacity = "1";
  }, 100);
});

/*  Export for potential module usage */
if (typeof module !== "undefined" && module.exports) {
  module.exports = ThemeManager;
}
