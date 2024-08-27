@extends('layouts.app')
@section('content')
    @include('components.forms.otp-verify-form')
@endsection

@section('scripts')
    <script>
        async function verifyOtp() {
            let otp = document.getElementById('otp').value;

            if (otp == "") {
                errorToast("Please enter otp.");
            } else {
                if (otp.length != 6) {
                    errorToast("Please enter 6 digit otp.");
                } else {
                    showLoader();
                    const response = await axios.post("{{ route('verify-otp') }}", {
                        otp: otp
                    });
                    hideLoader();

                    if (response.data.status == "success") {
                        successToast(response.data.message);
                        setTimeout(() => {
                            window.location.href = response.data.url;
                        }, 1000);
                    } else if (response.data.status == "failed") {
                        errorToast(response.data.message);
                        setTimeout(() => {
                            window.location.href = "{{ route('user.login') }}";
                        }, 1000);
                    } else {
                        errorToast(response.data.message);
                    }
                }

            }
        }
    </script>
@endsection
