function formatPhoneNumber(input) {
  let phoneNumber = input.value;

  if (phoneNumber.startsWith("0")) {
    phoneNumber = "62" + phoneNumber.slice(1);
  } else if (!phoneNumber.startsWith("62") && phoneNumber.length > 0) {
    phoneNumber = "62" + phoneNumber;
  }

  input.value = phoneNumber;
}
