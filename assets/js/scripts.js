function validateRegistrationForm(event) {
  event.preventDefault();
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  let errors = [];

  document.getElementById("name-error").textContent = "";
  document.getElementById("email-error").textContent = "";
  document.getElementById("password-error").textContent = "";

  if (name === "") {
    errors.push("Full Name is required.");
    document.getElementById("name-error").textContent =
      "Full Name is required.";
  } else if (name.length < 3) {
    errors.push("Full Name must be at least 3 characters long.");
    document.getElementById("name-error").textContent =
      "Full Name must be at least 3 characters long.";
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === "") {
    errors.push("Email Address is required.");
    document.getElementById("email-error").textContent =
      "Email Address is required.";
  } else if (!emailPattern.test(email)) {
    errors.push("Invalid Email Address.");
    document.getElementById("email-error").textContent =
      "Invalid Email Address.";
  }

  if (password === "") {
    errors.push("Password is required.");
    document.getElementById("password-error").textContent =
      "Password is required.";
  } else if (password.length < 6) {
    errors.push("Password must be at least 6 characters long.");
    document.getElementById("password-error").textContent =
      "Password must be at least 6 characters long.";
  }

  // If no errors, submit the form
  if (errors.length === 0) {
    document.getElementById("registration-form").submit();
  }
}

function validateLoginForm(event) {
  event.preventDefault();

  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  let errors = [];

  document.getElementById("email-error").textContent = "";
  document.getElementById("password-error").textContent = "";

  if (email === "") {
    errors.push("Email Address is required.");
    document.getElementById("email-error").textContent =
      "Email Address is required.";
  }

  if (password === "") {
    errors.push("Password is required.");
    document.getElementById("password-error").textContent =
      "Password is required.";
  }

  // If no errors, submit the form
  if (errors.length === 0) {
    document.getElementById("login-form").submit();
  }
}

function validateAttendeeRegForm(e) {
  e.preventDefault(); 
  
  const phone = document.getElementById("phone").value.trim();
  const name = document.getElementById("name").value.trim();
  const selectedEvent = document.getElementById("event").value.trim() ?? ''; 

  let errors = [];

  document.getElementById("name-error").textContent = "";
  document.getElementById("phone-error").textContent = "";
  document.getElementById("event-error").textContent = "";

  if (name === "") {
    errors.push("Name is required.");
    document.getElementById("name-error").textContent = "Name is required.";
  }

  if (selectedEvent === "") {
    errors.push("Please select an event.");
    document.getElementById("event-error").textContent = "Please select an event.";
  }

  const phonePattern = /^[0-9]{11}$/;
  if (phone === "") {
    errors.push("Phone number is required.");
    document.getElementById("phone-error").textContent = "Phone number is required.";
  } else if (!phonePattern.test(phone)) {
    errors.push("Please enter a valid 11-digit phone number.");
    document.getElementById("phone-error").textContent = "Please enter a valid 11-digit phone number.";
  }

  if (errors.length === 0) {
    document.getElementById("attendee-reg-form").submit();
  } 
}

