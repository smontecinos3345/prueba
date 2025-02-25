
document.getElementById("registerForm").addEventListener("submit", async function (event) {
  event.preventDefault();

  const formData = {
    name: document.getElementById("username").value,
    email: document.getElementById("email").value,
    password: document.getElementById("password").value,
    confirmPassword: document.getElementById("confirm-password").value
  };

  if (formData.password !== formData.confirmPassword) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: "Passwords don't match"
    });
    return;
  }

  try {
    const response = await fetch('/users', {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(formData)
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || "Registration failed");
    }

    // window.location.href = "/thankyou.html";
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Registration Failed',
      text: error.message
    });
  }
});
