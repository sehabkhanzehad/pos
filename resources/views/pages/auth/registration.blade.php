@extends('layouts.app')
@section('content')
    @include('components.forms.registration-form')
@endsection
@section('scripts')
   <script>
    async function registration() {
        let email = document.getElementById('email').value;
        let firstName = document.getElementById('firstName').value;
        let lastName = document.getElementById('lastName').value;
        let mobile = document.getElementById('mobile').value;
        let password = document.getElementById("password").value;

        if (email == "" && firstName == "" && lastName == "" && mobile == "" && password == "") {
            errorToast("Please enter your email and password.");
        } else if (email == "") {
            errorToast("Please enter your email.");
        } else if (firstName == "") {
            errorToast("Please enter your first name.");
        } else if (lastName == "") {
            errorToast("Please enter your last name.");
        } else if (mobile == "") {
            errorToast("Please enter your mobile number.");
        } else if (password == "") {
            errorToast("Please enter your password.");
        } else {
            showLoader();
            const response = await axios.post("{{ route('register') }}", {
                email: email,
                firstName: firstName,
                lastName: lastName,
                mobile: mobile,
                password: password
            });
            hideLoader();

            if (response.data.status == "success") {
                successToast(response.data.message);
                setTimeout(() => {
                    window.location.href = response.data.url;
                }, 1000);
            } else {
                errorToast(response.data.message);
            }
        }
    }
   </script>
@endsection
