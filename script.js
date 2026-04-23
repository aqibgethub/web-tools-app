let currentPassword = "";

/* =========================
   SWITCH TOOL + ACTIVE BTN
========================= */
function showTool(toolId) {
  let tools = document.querySelectorAll(".tool");
  let buttons = document.querySelectorAll(".tool-buttons button");

  tools.forEach(t => t.classList.remove("active"));
  buttons.forEach(btn => btn.classList.remove("active"));

  document.getElementById(toolId).classList.add("active");

  let activeBtn = document.querySelector(`[onclick="showTool('${toolId}')"]`);
  if (activeBtn) activeBtn.classList.add("active");
}

/* =========================
   AGE CALCULATOR
========================= */
function calculateAge() {
  let dob = document.getElementById("dob").value;

  if (!dob) {
    document.getElementById("age-result").innerText = "Select date first!";
    return;
  }

  let birth = new Date(dob);
  let today = new Date();
  let age = today.getFullYear() - birth.getFullYear();

  if (
    today < new Date(today.getFullYear(), birth.getMonth(), birth.getDate())
  ) {
    age--;
  }

  document.getElementById("age-result").innerText = "Your Age: " + age;
}

function resetAge() {
  document.getElementById("dob").value = "";
  document.getElementById("age-result").innerText = "";
}

/* =========================
   PASSWORD GENERATOR
========================= */
function updateLength(val) {
  document.getElementById("lengthValue").innerText = val;
}

function generatePassword() {
  let chars =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*";

  let length = document.getElementById("length").value;
  let pass = "";

  for (let i = 0; i < length; i++) {
    pass += chars.charAt(Math.floor(Math.random() * chars.length));
  }

  currentPassword = pass;
  document.getElementById("password-result").innerText = pass;
}

function copyPassword() {
  if (!currentPassword) return;

  navigator.clipboard.writeText(currentPassword);
  alert("Password copied!");
}

function resetPassword() {
  currentPassword = "";
  document.getElementById("password-result").innerText = "";
}

/* =========================
   BMI CALCULATOR
========================= */
function calculateBMI() {
  let weight = document.getElementById("weight").value;
  let height = document.getElementById("height").value;

  if (!weight || !height) {
    document.getElementById("bmi-result").innerText =
      "Please enter values";
    return;
  }

  height = height / 100;
  let bmi = weight / (height * height);

  let result = "";

  if (bmi < 18.5) result = "Underweight";
  else if (bmi < 24.9) result = "Normal";
  else if (bmi < 29.9) result = "Overweight";
  else result = "Obese";

  document.getElementById("bmi-result").innerText =
    "BMI: " + bmi.toFixed(2) + " (" + result + ")";
}

function resetBMI() {
  document.getElementById("weight").value = "";
  document.getElementById("height").value = "";
  document.getElementById("bmi-result").innerText = "";
}

/* =========================
   DEFAULT TOOL ON LOAD
========================= */
window.onload = function () {
  showTool("age");
};

/* =========================
   DARK / LIGHT THEME
========================= */
function toggleTheme() {
  document.body.classList.toggle("light");
}