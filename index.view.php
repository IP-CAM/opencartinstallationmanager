<html>

<head>
    <title>Opencart Install</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous">

    <style>
        body {background-color: #f6f6f6;}
        .container {box-shadow: 5px 10px 10px #eee;}
        .custom-alert {position: absolute; width: 100%; top: 0;}
    </style>
</head>

<body class="pt-5">
    <div class="container bg-white p-4 pb-5">

        <a href="" class="btn btn-outline-dark btn-sm float-right">Refresh Page</a>
        <h1 class="text-center mb-3">Opencart Installer (OCI)</h1>

        <ul class="nav nav-pills flex-column flex-sm-row mb-3" id="pills-tab" role="tablist">
            <li class="nav-item flex-sm-fill text-sm-center">
                <a class="nav-link" id="pills-install-tab" data-toggle="pill" href="#pills-install" role="tab"
                    aria-controls="pills-install" aria-selected="true">Add New</a>
            </li>
            <li class="nav-item flex-sm-fill text-sm-center">
                <a class="nav-link active" id="pills-list-tab" data-toggle="pill" href="#pills-list" role="tab" aria-controls="pills-list"
                    aria-selected="false">View List</a>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade" id="pills-install" role="tabpanel" aria-labelledby="pills-install-tab">
                <div class="card">
                    <div class="card-header">Install Store</div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="versinInput">Version:</label>
                                <select name="oc_versions" id="versinInput" class="form-control">
                                    <option>Please Select Version</option>
                                    <?php
                                        foreach($versions as $version){
                                            if($version != '.' && $version != '..'){
                                                if (strpos($version, '.zip') !== false) {
                                                    echo '<option value="'.$version.'">'.$version.'</option>';
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="storeInput">Website/Store Name:</label>
                                <input name="store" id="storeInput" type="text" class="form-control" required />
                            </div>

                            <div class="form-group">
                                <button type="submit" name="install" class="btn btn-primary">Install</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show active" id="pills-list" role="tabpanel" aria-labelledby="pills-list-tab">
                <div class="card">
                    <div class="card-header">Stores</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Version</th>
                                    <th class="text-right"></th>
                                </tr>
                            </thead>

                            <tbody>
                    <?php
                        $count = 1;
                        foreach($stores as $store) { 
                            if($store != '.' && $store != '..') { $version = getVersion($store); ?>
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>
                                        <?= $store ?>
                                    </td>
                                    <td>
                                        <?= $version ?>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= STORE_URL . $store; ?>" target="_blank" title="Visit Store" class="btn btn-info"><i
                                                class="far fa-eye"></i></a>

                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="store" value="<?= $store ?>">
                                            <button type="submit" name="refresh_store" title="Refresh Store" class="btn btn-warning"><i
                                                    class="fas fa-sync-alt"></i></button>
                                        </form>

                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="store" value="<?= $store ?>">
                                            <button type="submit" name="delete" title="Delete Store" class="btn btn-danger"><i
                                                    class="far fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>

                    <?php $count++;}} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
</body>

</html>