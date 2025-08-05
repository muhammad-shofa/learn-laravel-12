@vite(['resources/js/dashboard.js'])

@vite(['resources/css/app.css'])

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Article Management</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="p-5">
    <nav class="navbar navbar-expand-lg navbar-white border border-3 border-black bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <a class="navbar-brand" href="#0">Article Management</a>
        </div>
    </nav>

    <div class="my-5 mx-auto p-3 border border-3 border-black rounded shadow-sm">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-warning shadow m-2" data-bs-toggle="modal" data-bs-target="#addModal">
            Add Article
        </button>

        <!-- Add Artikel Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formAddArtikel"> <!-- Form untuk handle via jQuery -->
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Article</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="writer" class="form-label">Writer</label>
                                <input type="text" class="form-control" id="writer" name="writer" required>
                            </div>
                            <div class="mb-3">
                                <label for="publish_date" class="form-label">Publish Date</label>
                                <input type="date" class="form-control" id="publish_date" name="publish_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary save-add">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- article table -->
        <table id="articleTable" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Writer</th>
                    <th>Publish Date</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

        <!-- Edit Article Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formEditArticle">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editModalLabel">Edit Article</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_article_id" name="edit_article_id">

                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="edit_title" name="edit_title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_content" class="form-label">Content</label>
                                <textarea class="form-control" id="edit_content" name="edit_content" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_writer" class="form-label">Writer</label>
                                <input type="text" class="form-control" id="edit_writer" name="edit_writer" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_publish_date" class="form-label">Publish Date</label>
                                <input type="date" class="form-control" id="edit_publish_date" name="edit_publish_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="edit_category" name="edit_category" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary save-edit">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <!--  -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <h1>hh</h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete_article_id">
                        Are you sure want to delete this data?
                    </div>
                    <div class="modal-footer">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger confirm-delete">Delete</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Show Article Modal -->
        <div class="modal fade" id="showArticleContentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Article Content</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="articleContentBody"></p>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <h1>hh</h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete_article_id">
                        Are you sure want to delete this data?
                    </div>
                    <div class="modal-footer">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger confirm-delete">Delete</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- datatables -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <!-- swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>