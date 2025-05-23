@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-primary shadow-sm">
                            <i class="bi bi-gear-fill"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">CPU Traffic</span>
                            <span class="info-box-number">
                                10
                                <small>%</small>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-danger shadow-sm">
                            <i class="bi bi-hand-thumbs-up-fill"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Likes</span>
                            <span class="info-box-number">41,410</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- fix for small devices only -->
                <!-- <div class="clearfix hidden-md-up"></div> -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-success shadow-sm">
                            <i class="bi bi-cart-fill"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sales</span>
                            <span class="info-box-number">760</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-warning shadow-sm">
                            <i class="bi bi-people-fill"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">New Members</span>
                            <span class="info-box-number">2,000</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Recap Report</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-tool dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-wrench"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" role="menu">
                                        <a href="#" class="dropdown-item">Action</a>
                                        <a href="#" class="dropdown-item">Another action</a>
                                        <a href="#" class="dropdown-item"> Something else here </a>
                                        <a class="dropdown-divider"></a>
                                        <a href="#" class="dropdown-item">Separated link</a>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-center">
                                        <strong>Sales: 1 Jan, 2023 - 30 Jul, 2023</strong>
                                    </p>
                                    <div id="sales-chart"></div>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-4">
                                    <p class="text-center"><strong>Goal Completion</strong></p>
                                    <div class="progress-group">
                                        Add Products to Cart
                                        <span class="float-end"><b>160</b>/200</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar text-bg-primary" style="width: 80%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        Complete Purchase
                                        <span class="float-end"><b>310</b>/400</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar text-bg-danger" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        <span class="progress-text">Visit Premium Page</span>
                                        <span class="float-end"><b>480</b>/800</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar text-bg-success" style="width: 60%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        Send Inquiries
                                        <span class="float-end"><b>250</b>/500</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar text-bg-warning" style="width: 50%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!-- ./card-body -->
                        <div class="card-footer">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <div class="text-center border-end">
                                        <span class="text-success">
                                            <i class="bi bi-caret-up-fill"></i> 17%
                                        </span>
                                        <h5 class="fw-bold mb-0">$35,210.43</h5>
                                        <span class="text-uppercase">TOTAL REVENUE</span>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-3 col-6">
                                    <div class="text-center border-end">
                                        <span class="text-info"> <i class="bi bi-caret-left-fill"></i> 0% </span>
                                        <h5 class="fw-bold mb-0">$10,390.90</h5>
                                        <span class="text-uppercase">TOTAL COST</span>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-3 col-6">
                                    <div class="text-center border-end">
                                        <span class="text-success">
                                            <i class="bi bi-caret-up-fill"></i> 20%
                                        </span>
                                        <h5 class="fw-bold mb-0">$24,813.53</h5>
                                        <span class="text-uppercase">TOTAL PROFIT</span>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-3 col-6">
                                    <div class="text-center">
                                        <span class="text-danger">
                                            <i class="bi bi-caret-down-fill"></i> 18%
                                        </span>
                                        <h5 class="fw-bold mb-0">1200</h5>
                                        <span class="text-uppercase">GOAL COMPLETIONS</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Row-->
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row">
                <!-- Start col -->
                <div class="col-md-8">
                    <!--begin::Row-->
                    <div class="row g-4 mb-4">


                    </div>
                    <!--end::Row-->
                    <!--begin::Latest Order Widget-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Latest Orders</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Item</th>
                                            <th>Status</th>
                                            <th>Popularity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a
                                                    href="pages/examples/invoice.html"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR9842</a>
                                            </td>
                                            <td>Call of Duty IV</td>
                                            <td><span class="badge text-bg-success"> Shipped </span></td>
                                            <td>
                                                <div id="table-sparkline-1"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a
                                                    href="pages/examples/invoice.html"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR1848</a>
                                            </td>
                                            <td>Samsung Smart TV</td>
                                            <td><span class="badge text-bg-warning">Pending</span></td>
                                            <td>
                                                <div id="table-sparkline-2"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a
                                                    href="pages/examples/invoice.html"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR7429</a>
                                            </td>
                                            <td>iPhone 6 Plus</td>
                                            <td><span class="badge text-bg-danger"> Delivered </span></td>
                                            <td>
                                                <div id="table-sparkline-3"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a
                                                    href="pages/examples/invoice.html"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR7429</a>
                                            </td>
                                            <td>Samsung Smart TV</td>
                                            <td><span class="badge text-bg-info">Processing</span></td>
                                            <td>
                                                <div id="table-sparkline-4"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a
                                                    href="pages/examples/invoice.html"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR1848</a>
                                            </td>
                                            <td>Samsung Smart TV</td>
                                            <td><span class="badge text-bg-warning">Pending</span></td>
                                            <td>
                                                <div id="table-sparkline-5"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a
                                                    href="pages/examples/invoice.html"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR7429</a>
                                            </td>
                                            <td>iPhone 6 Plus</td>
                                            <td><span class="badge text-bg-danger"> Delivered </span></td>
                                            <td>
                                                <div id="table-sparkline-6"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a
                                                    href="pages/examples/invoice.html"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">OR9842</a>
                                            </td>
                                            <td>Call of Duty IV</td>
                                            <td><span class="badge text-bg-success">Shipped</span></td>
                                            <td>
                                                <div id="table-sparkline-7"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary float-start">
                                Place New Order
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-end">
                                View All Orders
                            </a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <!-- Info Boxes Style 2 -->
                    <div class="info-box mb-3 text-bg-warning">
                        <span class="info-box-icon"> <i class="bi bi-tag-fill"></i> </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Inventory</span>
                            <span class="info-box-number">5,200</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                    <div class="info-box mb-3 text-bg-success">
                        <span class="info-box-icon"> <i class="bi bi-heart-fill"></i> </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mentions</span>
                            <span class="info-box-number">92,050</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                    <div class="info-box mb-3 text-bg-danger">
                        <span class="info-box-icon"> <i class="bi bi-cloud-download"></i> </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Downloads</span>
                            <span class="info-box-number">114,381</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                    <div class="info-box mb-3 text-bg-info">
                        <span class="info-box-icon"> <i class="bi bi-chat-fill"></i> </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Direct Messages</span>
                            <span class="info-box-number">163,921</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Browser Usage</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-12">
                                    <div id="pie-chart"></div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer p-0">
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        United States of America
                                        <span class="float-end text-danger">
                                            <i class="bi bi-arrow-down fs-7"></i>
                                            12%
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        India
                                        <span class="float-end text-success">
                                            <i class="bi bi-arrow-up fs-7"></i> 4%
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        China
                                        <span class="float-end text-info">
                                            <i class="bi bi-arrow-left fs-7"></i> 0%
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.footer -->
                    </div>
                    <!-- /.card -->
                    <!-- PRODUCT LIST -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recently Added Products</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="px-2">
                                <div class="d-flex border-top py-2 px-1">
                                    <div class="col-2">
                                        <img
                                            src="../../dist/assets/img/default-150x150.png"
                                            alt="Product Image"
                                            class="img-size-50" />
                                    </div>
                                    <div class="col-10">
                                        <a href="javascript:void(0)" class="fw-bold">
                                            Samsung TV
                                            <span class="badge text-bg-warning float-end"> $1800 </span>
                                        </a>
                                        <div class="text-truncate">Samsung 32" 1080p 60Hz LED Smart HDTV.</div>
                                    </div>
                                </div>
                                <!-- /.item -->
                                <div class="d-flex border-top py-2 px-1">
                                    <div class="col-2">
                                        <img
                                            src="../../dist/assets/img/default-150x150.png"
                                            alt="Product Image"
                                            class="img-size-50" />
                                    </div>
                                    <div class="col-10">
                                        <a href="javascript:void(0)" class="fw-bold">
                                            Bicycle
                                            <span class="badge text-bg-info float-end"> $700 </span>
                                        </a>
                                        <div class="text-truncate">
                                            26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                        </div>
                                    </div>
                                </div>
                                <!-- /.item -->
                                <div class="d-flex border-top py-2 px-1">
                                    <div class="col-2">
                                        <img
                                            src="../../dist/assets/img/default-150x150.png"
                                            alt="Product Image"
                                            class="img-size-50" />
                                    </div>
                                    <div class="col-10">
                                        <a href="javascript:void(0)" class="fw-bold">
                                            Xbox One
                                            <span class="badge text-bg-danger float-end"> $350 </span>
                                        </a>
                                        <div class="text-truncate">
                                            Xbox One Console Bundle with Halo Master Chief Collection.
                                        </div>
                                    </div>
                                </div>
                                <!-- /.item -->
                                <div class="d-flex border-top py-2 px-1">
                                    <div class="col-2">
                                        <img
                                            src="../../dist/assets/img/default-150x150.png"
                                            alt="Product Image"
                                            class="img-size-50" />
                                    </div>
                                    <div class="col-10">
                                        <a href="javascript:void(0)" class="fw-bold">
                                            PlayStation 4
                                            <span class="badge text-bg-success float-end"> $399 </span>
                                        </a>
                                        <div class="text-truncate">PlayStation 4 500GB Console (PS4)</div>
                                    </div>
                                </div>
                                <!-- /.item -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-center">
                            <a href="javascript:void(0)" class="uppercase"> View All Products </a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
</main>
@endsection