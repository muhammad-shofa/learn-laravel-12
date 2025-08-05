$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Load article data
    function loadArticleData() {
        $("#articleTable").DataTable({
            destroy: true,
            paging: true,
            info: true,
            ordering: false,
            ajax: {
                url: "/api/article/get-article",
                type: "GET",
                dataSrc: function (response) {
                    if (response.success) {
                        return response.data;
                    } else {
                        console.error(response.error);
                        return [];
                    }
                },
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                },
                { data: "judul" },
                // { data: "konten" },
                {
                    data: "konten",
                    render: function (data, type, row) {
                        return `
                        <button class="btn-view-content btn btn-success" 
                            data-content="${encodeURIComponent(row.konten)}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#showArticleContentModal">
                            <i class="fa-solid fa-eye"></i>
                        </button>`;
                    },
                },
                { data: "penulis" },
                { data: "tanggal_publish" },
                { data: "kategori" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn-edit btn btn-primary" data-article_id="${row.id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn-delete btn btn-danger" data-article_id="${row.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                    },
                },
            ],
        });
    }

    loadArticleData();

    // Add article
    $(document).on("click", ".save-add", function () {
        let title = $("#title").val();
        let content = $("#content").val();
        let writer = $("#writer").val();
        let publish_date = $("#publish_date").val();
        let category = $("#category").val();

        $.ajax({
            url: "/api/article/add-article",
            method: "POST",
            data: {
                title: title,
                content: content,
                writer: writer,
                publish_date: publish_date,
                category: category,
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Article has been added successfully.",
                });
                $("#addModal").modal("hide");
                $("#formAddArtikel")[0].reset();
                loadArticleData();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Failed",
                    text: "Unable to add the article.",
                });
            },
        });
    });

    // Edit article - show data
    $(document).on("click", ".btn-edit", function () {
        let article_id = $(this).data("article_id");

        $.ajax({
            url: "/api/article/get-article/" + article_id,
            type: "GET",
            dataType: "json",
            success: (response) => {
                if (response.success) {
                    $("#edit_article_id").val(response.data.id);
                    $("#edit_title").val(response.data.judul);
                    $("#edit_content").val(response.data.konten);
                    $("#edit_writer").val(response.data.penulis);
                    $("#edit_category").val(response.data.kategori);
                    $("#edit_publish_date").val(response.data.tanggal_publish);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An error occurred while fetching article data.",
                });
                console.error("AJAX Error: " + status + " - " + error);
            },
        });
    });

    // Save edit article
    $(document).on("click", ".save-edit", function () {
        let article_id = $("#edit_article_id").val();
        let title = $("#edit_title").val();
        let content = $("#edit_content").val();
        let writer = $("#edit_writer").val();
        let category = $("#edit_category").val();
        let publish_date = $("#edit_publish_date").val();

        $.ajax({
            url: "/api/article/update-article/" + article_id,
            type: "PUT",
            dataType: "json",
            data: {
                title: title,
                content: content,
                writer: writer,
                category: category,
                publish_date: publish_date,
            },
            success: (response) => {
                if (response.success) {
                    $("#editModal").modal("hide");
                    Swal.fire({
                        icon: "success",
                        title: "Updated",
                        text: "Article has been updated successfully.",
                    });
                    loadArticleData();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An error occurred while updating the article.",
                });
                console.error("AJAX Error: " + status + " - " + error);
            },
        });
    });

    // show content
    // articleContentBody
    $(document).on("click", ".btn-view-content", function () {
        const rawContent = decodeURIComponent($(this).data("content"));
        $("#articleContentBody").html(rawContent);
    });

    // Delete article with confirmation
    $(document).on("click", ".btn-delete", function () {
        let article_id = $(this).data("article_id");

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#aaa",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/article/delete-article/" + article_id,
                    type: "DELETE",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Deleted",
                                text: "Article has been deleted successfully.",
                            });
                            loadArticleData();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Failed",
                                text: response.message,
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "An error occurred while deleting the article.",
                        });
                        console.error("AJAX Error: " + status + " - " + error);
                    },
                });
            }
        });
    });
});
