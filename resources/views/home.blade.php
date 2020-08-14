@extends('layouts.app')

@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- ============================================================== -->
            <!-- pageheader  -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">E-commerce Dashboard Template </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel
                            mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">E-Commerce Dashboard
                                        Template
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end pageheader  -->
            <!-- ============================================================== -->
            <div class="ecommerce-widget">

                <div class="row">
                    <!-- ============================================================== -->
                    <!-- sales  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="card border-3 border-top border-top-primary">
                            <div class="card-body">
                                <h1 class="text-muted">Ventas</h1>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="card border-3 border-top border-top-danger">
                            <div class="card-body">
                                <h1 class="text-muted">Clientes</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- ============================================================== -->

                    <!-- ============================================================== -->

                    <!-- recent orders  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-9 col-lg-12 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Recent Orders</h5>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Image</th>
                                            <th class="border-0">Product Name</th>
                                            <th class="border-0">Product Id</th>
                                            <th class="border-0">Quantity</th>
                                            <th class="border-0">Price</th>
                                            <th class="border-0">Order Time</th>
                                            <th class="border-0">Customer</th>
                                            <th class="border-0">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="m-r-10"><img src="assets/images/product-pic.jpg"
                                                                         alt="user" class="rounded" width="45">
                                                </div>
                                            </td>
                                            <td>Product #1</td>
                                            <td>id000001</td>
                                            <td>20</td>
                                            <td>$80.00</td>
                                            <td>27-08-2018 01:22:12</td>
                                            <td>Patricia J. King</td>
                                            <td><span class="badge-dot badge-brand mr-1"></span>InTransit</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="m-r-10"><img src="assets/images/product-pic-2.jpg"
                                                                         alt="user" class="rounded" width="45">
                                                </div>
                                            </td>
                                            <td>Product #2</td>
                                            <td>id000002</td>
                                            <td>12</td>
                                            <td>$180.00</td>
                                            <td>25-08-2018 21:12:56</td>
                                            <td>Rachel J. Wicker</td>
                                            <td><span class="badge-dot badge-success mr-1"></span>Delivered</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="m-r-10"><img src="assets/images/product-pic-3.jpg"
                                                                         alt="user" class="rounded" width="45">
                                                </div>
                                            </td>
                                            <td>Product #3</td>
                                            <td>id000003</td>
                                            <td>23</td>
                                            <td>$820.00</td>
                                            <td>24-08-2018 14:12:77</td>
                                            <td>Michael K. Ledford</td>
                                            <td><span class="badge-dot badge-success mr-1"></span>Delivered</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="m-r-10"><img src="assets/images/product-pic-4.jpg"
                                                                         alt="user" class="rounded" width="45">
                                                </div>
                                            </td>
                                            <td>Product #4</td>
                                            <td>id000004</td>
                                            <td>34</td>
                                            <td>$340.00</td>
                                            <td>23-08-2018 09:12:35</td>
                                            <td>Michael K. Ledford</td>
                                            <td><span class="badge-dot badge-success mr-1"></span>Delivered</td>
                                        </tr>
                                        <tr>
                                            <td colspan="9"><a href="#" class="btn btn-outline-light float-right">View
                                                    Details</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end recent orders  -->


                    <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12">
                        <!-- ============================================================== -->
                        <!-- top perfomimg  -->
                        <!-- ============================================================== -->
                        <div class="card">
                            <h5 class="card-header">Top Performing Campaigns</h5>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table no-wrap p-table">
                                        <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">Campaign</th>
                                            <th class="border-0">Visits</th>
                                            <th class="border-0">Revenue</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Campaign#1</td>
                                            <td>98,789</td>
                                            <td>$4563</td>
                                        </tr>
                                        <tr>
                                            <td>Campaign#2</td>
                                            <td>2,789</td>
                                            <td>$325</td>
                                        </tr>
                                        <tr>
                                            <td>Campaign#3</td>
                                            <td>1,459</td>
                                            <td>$225</td>
                                        </tr>
                                        <tr>
                                            <td>Campaign#4</td>
                                            <td>5,035</td>
                                            <td>$856</td>
                                        </tr>
                                        <tr>
                                            <td>Campaign#5</td>
                                            <td>10,000</td>
                                            <td>$1000</td>
                                        </tr>
                                        <tr>
                                            <td>Campaign#5</td>
                                            <td>10,000</td>
                                            <td>$1000</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <a href="#" class="btn btn-outline-light float-right">Details</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end top perfomimg  -->
                        <!-- ============================================================== -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
