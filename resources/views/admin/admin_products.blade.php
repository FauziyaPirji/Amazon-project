<!DOCTYPE html>
<html>
  <head>
    <title>Amazon Project-Admin</title>

    <!-- This code is needed for responsive design to work.
      (Responsive design = make the website look good on
      smaller screen sizes like a phone or a tablet). -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Load a font called Roboto from Google Fonts. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Here are the CSS files for this page. -->
    <link rel="stylesheet" href="{{ asset('assets/css/shared/admin-general.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/shared/amazon-admin-header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  </head>
  <body>
    @include('admin.view.admin_navbar');
    
        <div class="container-fluid" style="margin-top:75px">
            <div class="col-lg-12">
                <div class="row">
                <!-- FORM Panel -->
                    <div class="col-md-4">
                        <form action="{{ route('product.add') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card text-dark">
                                <div class="card-header" style="background-color: rgb(111 202 203);">
                                    Create New Product
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="control-label">Product Name: </label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="image" class="control-label">Prodcut Image:</label>
                                        <input type="file" name="image" id="image" accept=".png,,jpeg,.jpg" class="form-control" required style="border:none;">
                                        <small id="Info" class="form-text text-muted mx-3">Please .png,.jpeg,.jpg file upload.</small>
                                            @error('image')
                                                 <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label">Product Price: </label>
                                        <input type="text" class="form-control" name="price" id="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label">Product Category: [Optional]</label>
                                        <input type="text" class="form-control" name="category" id="category" value="{{ old('category') }}">
                                        @error('category')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>  
                                <button type="submit" name="submit" id="submit" class="btn btn-lg btn-primary btn-block"> Create </button>
                            </div>
                        </form>
                    </div>
                <!-- FORM Panel -->
                <!-- Table Panel -->
                    <div class="col-md-8 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead style="background-color: rgb(111 202 203);">
                                        <tr>
                                            <th class="text-center" style="width:3%;">Id</th>
                                            <th class="text-center">Img</th>
                                            <th class="text-center" style="width:35%;">Name</th>
                                            <th class="text-center" style="width:15%;">Price</th>
                                            <th class="text-center" style="width:15%;">Category</th>
                                            <th class="text-center" style="width:18%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach($Products as $product)
                                            <tr>
                                                <td>{{ $product->id }}</td>
                                                <td><img src="{{ asset('/storage/'. $product->product_image) }}" class="card-img-top" alt="image for this product" width="100px" height="100px"></td>
                                                <td>{{ $product->name }}</td>
                                                <td>${{ number_format(round($product->price/100, 2), 2) }}</td>
                                                <td>{{ $product->type }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" style="margin-left:35px;" data-toggle="modal" data-target="#editModal{{ $product->id }}">Edit</button> 
                                                    <form action="{{ route('product.delete', $product->id) }}" method="get">
                                                        <button name="removeCategorie" class="btn btn-sm btn-danger" style="margin-left:35px;margin-top:4px;">Delete</button>
                                                    </form>
                                                </td>
                                                <!-- Modal -->
                                                <div class="modal fade text-dark" id="editModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="width: -webkit-fill-available;">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background-color: rgb(111 202 203);">
                                                                <h5 class="modal-title" id="editModal">Product Id: <b>{{ $product->id }}</b></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group row my-0">
                                                                        <div class="form-group col-md-6">
                                                                            <b><label for="image">Upload Photo:</label></b>
                                                                            <input type="file" name="image" id="image">
                                                                            @if($product->product_image)
                                                                                <img src="{{ asset('/storage/'. $product->product_image) }}" width="100">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-left my-2">
                                                                        <b><label for="name">Name</label></b>
                                                                        <input class="form-control" id="name" name="name" value="{{ $product->name }}" type="text" required>
                                                                    </div>
                                                                    <div class="text-left my-2">
                                                                        <b><label for="price">Price</label></b>
                                                                        <input class="form-control" id="price" name="price" value="{{ $product->price }}" rows="2" type="number" required>
                                                                    </div>
                                                                    <div class="text-left my-2">
                                                                        <b><label for="category">Category [Option]</label></b>
                                                                        <input class="form-control" id="category" name="category" value="{{ $product->type }}" rows="2" type="text">
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" name="updateProduct">Update</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  </body>
</html>
