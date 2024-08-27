@extends('layouts.app')
@section('content')
    @include('components.forms.otp-send-form')
@endsection
@section('scripts')
<script>
    async function sendOtp() {

        let email = document.getElementById('email').value;
        if (email == "") {
            errorToast("Please enter your email.");
        } else {
           showLoader();
            const response = await axios.post("{{ route('send-otp') }}", {
                email: email
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
