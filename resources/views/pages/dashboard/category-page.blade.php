@extends('layouts.sidenav')
@section('content')
    @include('components.dashboard.category.category-list')
    @include('components.dashboard.category.category-delete')
    @include('components.dashboard.category.category-create')
    @include('components.dashboard.category.category-update')
@endsection

@section('scripts')
    <script>
        // For Category List
        categoryList();
        async function categoryList() {
            showLoader();
            const respons = await axios.get("{{ route('category.list') }}");
            hideLoader();

            // $("dataTable").DataTable().destroy();
            // $("#tableList").empty();

            respons.data.forEach(function(item, index) {
                let row = `<tr>
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td>
                            <i style="cursor:pointer; color:green; border: 1px solid green; background-color: white; padding: 3px 5px; border-radius: 5px" class="bi bi-pencil-square" onclick="editCategory(${item.id})" data-bs-toggle="modal" data-bs-target="#update-modal"></i>
                            <i style="cursor:pointer; color:red; border: 1px solid red; background-color: white; padding: 3px 5px; border-radius: 5px" onclick="deleteCategory(${item.id})" data-bs-toggle="modal" data-bs-target="#delete-modal" class="bi bi-trash"></i>

                        </td>
                    </tr>`;
                $("#tableList").append(row);
            });

            new DataTable('#tableData', {
                order: [
                    [0, 'asc']
                ],
                lengthMenu: [5, 10, 15, 20, 30]
            });

        }


        // For Category Create

        document.getElementById("store").addEventListener("click", async function() {
            let categoryName = document.getElementById("categoryName").value;

            if (categoryName == "") {
                errorToast("Please enter category name.");
            } else {


                showLoader();
                const respons = await axios.post("{{ route('category.create') }}", {
                    name: categoryName
                });
                hideLoader();

                if (respons.data == "success") {
                    document.getElementById("modal-close").click();
                    succseeToast(respons.data.message);

                } else {
                    errorToast("Something went wrong.");
                }
            }




        });





    </script>
@endsection
