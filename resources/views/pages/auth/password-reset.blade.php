@extends('layouts.app')
@section('content')
    @include('components.forms.password-reset-form')
@endsection
@section('scripts')
<script>
    async function resetPassword() {

        let password = document.getElementById('password').value;
        let cpassword = document.getElementById('cpassword').value;

        if (password == "" && cpassword == "") {
            errorToast("Please enter your password.");
        } else if (password == "") {
            errorToast("Please enter your password.");
        } else if (cpassword == "") {
            errorToast("Please confirm your password.");
        } else if (password != cpassword) {
            errorToast("Password does not match.");
        } else {
            showLoader();
            const response = await axios.post("{{ route('reset-password') }}", {
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
