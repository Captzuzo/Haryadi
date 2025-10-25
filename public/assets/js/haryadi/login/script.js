// assets/js/haryadi/login/script.js

class LoginManager {
  constructor() {
    this.form = document.getElementById("loginForm");
    this.alert = document.getElementById("alert");
    this.submitBtn = document.getElementById("submitBtn");
    this.btnText = this.submitBtn?.querySelector(".btn-text");
    this.btnLoading = this.submitBtn?.querySelector(".btn-loading");

    console.log("🔧 Manajer Login diinisialisasi");

    this.init();
  }

  init() {
    this.bindEvents();
    this.addShakeAnimation();

    // Auto-focus pada field email
    const emailInput = document.getElementById("email");
    if (emailInput) {
      emailInput.focus();
    }
  }

  bindEvents() {
    if (this.form) {
      this.form.addEventListener("submit", (e) => this.handleLogin(e));
      console.log("✅ Event listener form terpasang");
    } else {
      console.error("❌ Form login tidak ditemukan!");
      return;
    }

    // Support tombol Enter
    document.addEventListener("keypress", (e) => {
      if (e.key === "Enter" && this.submitBtn && !this.submitBtn.disabled) {
        console.log("⌨️ Tombol Enter ditekan, mengirim form");
        this.form.dispatchEvent(new Event("submit"));
      }
    });

    // Sembunyikan alert otomatis setelah 5 detik
    document.addEventListener("click", () => {
      if (this.alert && this.alert.style.display === "block") {
        setTimeout(() => {
          this.alert.style.display = "none";
        }, 5000);
      }
    });
  }

  async handleLogin(e) {
    e.preventDefault();
    this.showLoading(true);

    try {
      // 1. Get form data
      const formData = new FormData(this.form);
      const loginData = Object.fromEntries(formData);

      // 2. Send request
      const response = await fetch("/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(loginData),
      });

      // 3. Get response as text
      const responseText = await response.text();

      // 4. Parse JSON (DECLARE result HERE)
      let result = JSON.parse(responseText);

      // 5. NOW use result
      if (result.success) {
        // Success logic
        this.showAlert(result.message, "success");
        setTimeout(() => {
          window.location.href = result.data?.redirect || "/dashboard";
        }, 1000);
      } else {
        // Error logic
        this.showAlert(result.message, "error");
      }
    } catch (error) {
      // Error handling
      this.showAlert(error.message, "error");
    } finally {
      this.showLoading(false);
    }
  }

  showLoading(show) {
    if (!this.btnText || !this.btnLoading) return;

    if (show) {
      this.btnText.style.display = "none";
      this.btnLoading.style.display = "inline";
      this.submitBtn.disabled = true;
    } else {
      this.btnText.style.display = "inline";
      this.btnLoading.style.display = "none";
      this.submitBtn.disabled = false;
    }
  }

  showAlert(message, type) {
    if (!this.alert) {
      console.error("❌ Elemen alert tidak ditemukan!");
      return;
    }

    this.alert.textContent = message;
    this.alert.className = "alert";

    switch (type) {
      case "success":
        this.alert.classList.add("alert-success");
        break;
      case "error":
        this.alert.classList.add("alert-error");
        this.alert.classList.add("shake");
        setTimeout(() => {
          this.alert.classList.remove("shake");
        }, 500);
        break;
    }

    this.alert.style.display = "block";
    console.log("📢 Alert ditampilkan:", message);
  }

  addShakeAnimation() {
    if (!document.getElementById("shake-style")) {
      const style = document.createElement("style");
      style.id = "shake-style";
      style.textContent = `
        @keyframes shake {
          0%, 100% { transform: translateX(0); }
          25% { transform: translateX(-5px); }
          75% { transform: translateX(5px); }
        }
        .shake {
          animation: shake 0.5s ease-in-out;
        }
      `;
      document.head.appendChild(style);
    }
  }
}

// Inisialisasi ketika DOM sudah dimuat
document.addEventListener("DOMContentLoaded", function () {
  console.log("🚀 DOM dimuat, menginisialisasi Manajer Login...");
  new LoginManager();

  // Opsional: Isi otomatis kredensial demo untuk testing
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");

  if (emailInput && passwordInput && window.location.hostname === "localhost") {
    // Hanya isi otomatis di localhost untuk testing
    emailInput.value = "admin@haryadi.com";
    passwordInput.value = "password123";
    console.log("🔧 Kredensial demo diisi otomatis");
  }
});
