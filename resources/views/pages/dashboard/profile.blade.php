@extends('layouts.sidenav')
@section('content')
    @include('components.dashboard.profile-form')
@endsection

@section('scripts')
    <script>
        getUserDetails();
        async function getUserDetails() {
            showLoader();
            const response = await axios.get("{{ route('user.details') }}");
            hideLoader();

            if (response.data.status == "success") {
                document.getElementById('email').value = response.data.data.email;
                document.getElementById('firstName').value = response.data.data.firstName;
                document.getElementById('lastName').value = response.data.data.lastName;
                document.getElementById('mobile').value = response.data.data.mobile;
                document.getElementById('password').value = response.data.data.password;
            } else {
                errorToast(response.data.message);
            }
        }


        async function update() {

            // let email = document.getElementById('email').value;
            let firstName = document.getElementById('firstName').value;
            let lastName = document.getElementById('lastName').value;
            let mobile = document.getElementById("mobile").value;
            let password = document.getElementById("password").value;


            if (email == "" && firstName == "" && lastName == "" && mobile == "" && password == "") {
                errorToast("Please enter your details");
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
                const response = await axios.post("{{ route('profile.update') }}", {
                    firstName: firstName,
                    lastName: lastName,
                    mobile: mobile,
                    password: password
                });
                hideLoader();

                if (response.data.status == "success") {
                    successToast(response.data.message);
                    await getUserDetails();
                } else {
                    errorToast(response.data.message);
                }
            }
        }
    </script>
@endsection
