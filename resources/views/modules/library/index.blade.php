@extends('layout.app')
@section('page-content')

<div class="page-header">
  <div class="row">
      <div class="col">
          <h3 class="page-title">@yield('page-name')
              <ul class="breadcrumb">
                 <h4>Library</h4>
              </ul>
      </div>
       <div class="col text-right">
          <div>
              <a href="#" data-toggle="modal" data-target="#addBookModal" data-toggle="tooltip" data-placement="bottom"
                  title="Add staff" class="btn btn-sm btn-primary shadow-sm">Add Book</a>
              <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                  data-target="#addCategoryModal"><i class=""></i>Add Category</a>
                  <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                  data-target="#borrowBookModal"><i class=""></i>Borrow Book</a> 
                  <a href="#" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                  data-target="#recommendBookModal"><i class=""></i>Recommend Book</a>
          </div>
      </div> 
  </div>
</div>
<!-- Table tabs -->
<!-- Content Column -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
      <a class="nav-link active" id="available-books-navlink" data-toggle="tab" href="#available-books" role="tab"
          aria-controls="alllunch" aria-selected="false">Available Books</a>
  </li>

  <li class="nav-item">
      <a class="nav-link " id="borrowed-books-navlink" data-toggle="tab" href="#borrowed-books" role="tab" aria-controls="lunch"
          aria-selected="false">Borrowed Books </a>
  </li>

  <li class="nav-item">
      <a class="nav-link " id="returned-books-navlink" data-toggle="tab" href="#returned-books" role="tab" aria-controls="return"
          aria-selected="false">Returned Books </a>
  </li>

  <li class="nav-item">
      <a class="nav-link " id="due-books-navlink" data-toggle="tab" href="#due-books" role="tab" aria-controls="due"
          aria-selected="false">Due Books </a>
  </li>

  <li class="nav-item">
      <a class="nav-link " id="overdue-due-books-navlink" data-toggle="tab" href="#overdue-books" role="tab" aria-controls="overdue"
          aria-selected="false">Over Due Books </a>
  </li>


  <li class="nav-item">
      <a class="nav-link " id="Book-quantity-navlink" data-toggle="tab" href="#lunch" role="tab" aria-controls="menu"
          aria-selected="false">Book Quantity</a>
  </li>
</ul>

<div class="tab-content">
  <div  role="tabpanel"  class=" tab-pane active card shadow mb-4" id="available-books">
    <div class="card-header">
        <h4 class="card-title">Total books <span>0</span></h4>
    </div>
    
    <div class="card-body">
        <div class="table-">
            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                width='100%' id="available-books">
                <thead>
                    <tr>
                        <th>Book Code</th>
                        <th>Book Title</th>
                        <th>Book Edition</th>
                        <th>Book Author</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data is fetched here using ajax --}}
                </tbody>
            </table>
        </div>
    </div>
  </div>
  <!--borrowed books table-->
  <div  role="tabpanel"  class=" tab-pane  card shadow mb-4" id="borrowed-books">
    <div class="card-header">
        <h4 class="card-title">Total books <span>0</span></h4>
    </div>
    
    <div class="card-body">
        <div class="table-">
            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                width='100%' id="available-books">
                <thead>
                    <tr>
                        <th>Name Of Student</th>
                        <th>Book Borrowed</th>
                        <th>Date Borrowed</th>
                        <th>Due Date</th>
                        <th>Return Status</th>
                        <th>Submit Book</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data is fetched here using ajax --}}
                </tbody>
            </table>
        </div>
    </div>
  </div>
  <!--retuned books-->
  <div  role="tabpanel"  class=" tab-pane  card shadow mb-4" id="returned-books">
    <div class="card-header">
        <h4 class="card-title">Total books <span>0</span></h4>
    </div>
    
    <div class="card-body">
        <div class="table-">
            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                width='100%' id="available-books">
                <thead>
                    <tr>
                      <th>Name Of Student</th>
                      <th>Book Borrowed</th>
                      <th>Date Borrowed</th>
                      <th>Due Date</th>
                      <th>Returned Date</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data is fetched here using ajax --}}
                </tbody>
            </table>
        </div>
    </div>
  </div>
  <!--Due books-->
   
   <div  role="tabpanel"  class=" tab-pane  card shadow mb-4" id="due-books">
    <div class="card-header">
        <h4 class="card-title">Total books <span>0</span></h4>
    </div>
    
    <div class="card-body">
        <div class="table-">
            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                width='100%' id="available-books">
                <thead>
                    <tr>
                      <th>Name Of Student</th>
                      <th>Book Borrowed</th>
                      <th>Date Borrowed</th>
                      <th>Due Date</th>
                       
                    </tr>
                </thead>
                <tbody>
                    {{-- Data is fetched here using ajax --}}
                </tbody>
            </table>
        </div>
    </div>
  </div>
  <!--overdue books-->
  <div  role="tabpanel"  class=" tab-pane  card shadow mb-4" id="overdue-books">
    <div class="card-header">
        <h4 class="card-title">Total books <span>0</span></h4>
    </div>
    
    <div class="card-body">
        <div class="table-">
            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                width='100%' id="available-books">
                <thead>
                    <tr>
                      <th>Name Of Student</th>
                      <th>Book Borrowed</th>
                      <th>Date Borrowed</th>
                      <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data is fetched here using ajax --}}
                </tbody>
            </table>
        </div>
    </div>
  </div>
  <!--books quantity-->
  <div  role="tabpanel"  class=" tab-pane  card shadow mb-4" id="books-quantity">
    <div class="card-header">
        <h4 class="card-title">Total books <span>0</span></h4>
    </div>
    
    <div class="card-body">
        <div class="table-">
            <table class="table table-sm table-bordered table-striped table-hover dataTable js-exportable"
                width='100%' id="available-books">
                <thead>
                    <tr>
                      <th>Name Of Student</th>
                      <th>Book Borrowed</th>
                      <th>Date Borrowed</th>
                      <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data is fetched here using ajax --}}
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div>
<!--add books modal-->
<div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Add Book</h5>
                <a href="#"><span class="close text-white" data-dismiss="modal" aria-label="Close"
                        aria-hidden="false">×</span></a>
            </div>
            <div class="modal-body">
                <form id="add-book-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="J3RZ49qwENHR8NyHwQ1olPRDqRjYsgiNbKDqabJh">                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Book Title <span class="font-weight-bold text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="book_title" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Book Author</label>
                                <input type="text" class="form-control form-control-sm" name="book_author">
                            </div>
                        </div>
                    </div>
                    <!--First row-->
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Book Edition</label>
                                <input type="text" class="form-control form-control-sm" name="book_edition">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Book Category <span class="font-weight-bold text-danger">*</span></label>
                                <select name="book_subject" class="form-control form-control-sm select2" required>
                                    <option value="" class="text-gray-100">--Select--</option>
                                                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Picture</label>
                                <input type="file" class="form-control form-control-sm" name="image">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-light btn-sm" form="add-book-form" type="reset">Reset</button>
                <button class="btn btn-primary btn-sm" form="add-book-form" type="submit" name="submit"> <i
                        class=""></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<!--borrow a book-->
<div class="modal fade" id="borrowBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Borrow Book</h5>
                <a href="#"><span class="close text-white" data-dismiss="modal" aria-label="Close"
                        aria-hidden="false">×</span></a>
            </div>
            <div class="modal-body">
                <form id="add-borrow-book-form">
                    <input type="hidden" name="_token" value="J3RZ49qwENHR8NyHwQ1olPRDqRjYsgiNbKDqabJh">                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Book Title <span class="font-weight-bold text-danger">*</span></label>
                                <select name="book_title" class="form-control form-control-sm select2" required>
                                    <option value="" class="text-gray-100">--Select--</option>
                                                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Borrower <span class="font-weight-bold text-danger">*</span></label>
                                <select name="borrower" class="form-control form-control-sm select2" required>
                                    <option value="" class="text-gray-100">--Select--</option>
                                                                        <option value="GIT0003">SUNSHINE AMI TUNNEL</option>
                                                                        <option value="GIT0024">DOMINIC  MENSAH</option>
                                                                        <option value="GIT0031">FRANCIS  NYAME</option>
                                                                        <option value="GIT0019">PRINCE  NKRUMAH</option>
                                                                        <option value="GIT0008">FANTA  IDDRISU</option>
                                                                        <option value="GIT0005">PRINCE  BEMPONG</option>
                                                                        <option value="GIT0028">MICHAEL  TAMAKLOE</option>
                                                                        <option value="GIT0001">TOM ACE TUNNEL</option>
                                                                        <option value="GIT0032">IMMANUEL  OKELY</option>
                                                                        <option value="GIT0014">ANIES  ALHASSAN</option>
                                                                        <option value="GIT0029">MONICA  MINTAH</option>
                                                                        <option value="GIT0026">MANUELLA  BEDIAKO</option>
                                                                        <option value="GIT0013">PETER  OBI</option>
                                                                        <option value="GIT0010">RICHELLE  MORLA</option>
                                                                        <option value="GIT0012">MABEL  MORLA</option>
                                                                        <option value="GIT0020">HELEN  MENSAH</option>
                                                                        <option value="GIT0018">JUSTICE  ABOAGYE</option>
                                                                        <option value="GIT0025">ELORM  BAAH</option>
                                                                        <option value="GIT0017">RUKAYATU  QASIM</option>
                                                                        <option value="GIT0016">HABIBA  QASIM</option>
                                                                        <option value="GIT0021">BEATRICE  NYARKO</option>
                                                                        <option value="GIT0006">AZEEZAH  IDDRISU</option>
                                                                        <option value="GIT0022">COMFORT  YEBOAH</option>
                                                                        <option value="GIT0007">JOSELYN  DZOTEPE</option>
                                                                        <option value="GIT0023">LINDA  AMENYO</option>
                                                                        <option value="GIT0004">RAZAK ABDUL IBRAHIM</option>
                                                                        <option value="GIT0015">FRANCE  EBO</option>
                                                                        <option value="GIT0009">MOHAMMED  TAWFIK</option>
                                                                        <option value="GIT0002">PRECIOUS ETORNAM TUNNEL</option>
                                                                        <option value="GIT0027">NANA  BOAKYE</option>
                                                                        <option value="GIT0011">LUCY  MORLA</option>
                                                                        <option value="GIT0030">JAMES  KWAKYE</option>
                                                                    </select>
                            </div>
                        </div>
                    </div>
                    <!--First row-->
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Date Borrowed <span class="font-weight-bold text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" name="date_borrowed" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Date Returned <span class="font-weight-bold text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" name="return_date" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-light btn-sm" form="add-borrow-book-form" type="reset">Reset</button>
                <button class="btn btn-primary btn-sm" form="add-borrow-book-form" type="submit" name="submit"> <i
                        class=""></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<!---adding a  book category-->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Add Book Category</h5>
                <a href="#"><span class="close text-white" data-dismiss="modal" aria-label="Close"
                        aria-hidden="false">×</span></a>
            </div>
            <div class="modal-body">
                <form id="add-book-subject-form">
                    <input type="hidden" name="_token" value="J3RZ49qwENHR8NyHwQ1olPRDqRjYsgiNbKDqabJh">                    <div class="form-group">
                        <label for="">Category</label>
                        <input type="text" name="book_subject" placeholder="Enter book category eg.Novel,Geography,Science,History"
                            class="form-control form-control-sm" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-light btn-sm" form="add-book-subject-form" type="reset">Reset</button>
                <button class="btn btn-primary btn-sm" form="add-book-subject-form" type="submit" name="submit"> <i
                        class=""></i> Submit</button>
            </div>
        </div>
    </div>
</div>
<!--recommend book-->
<div class="modal fade" id="recommendBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Recommend Book</h5>
                <a href="#"><span class="close text-white" data-dismiss="modal" aria-label="Close"
                        aria-hidden="false">×</span></a>
            </div>
            <div class="modal-body">
                <form id="rec-book-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="J3RZ49qwENHR8NyHwQ1olPRDqRjYsgiNbKDqabJh">                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Book Title <span class="font-weight-bold text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="book_title" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Book Author</label>
                                <input type="text" class="form-control form-control-sm" name="book_author">
                            </div>
                        </div>
                    </div>
                    <!--First row-->
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Book Edition</label>
                                <input type="text" class="form-control form-control-sm" name="book_edition">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Book Category <span class="font-weight-bold text-danger">*</span></label>
                                <select name="book_subject" class="form-control form-control-sm select2" required>
                                    <option value="" class="text-gray-100">--Select--</option>
                                                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Grade <span class="font-weight-bold text-danger">*</span></label>
                                <select name="book_subject" class="form-control form-control-sm select2" required>
                                    <option value="" class="text-gray-100">--Select--</option>
                                                                        <option value="NUR02">NURSERY 2</option>
                                                                        <option value="KG01">KINDERGARTEN 1</option>
                                                                        <option value="KG02">KINDERGARTEN 2</option>
                                                                        <option value="PR04">GRADE 4</option>
                                                                        <option value="PR06">GRADE 6</option>
                                                                        <option value="PR01">GRADE 1</option>
                                                                        <option value="PR02">GRADE 2</option>
                                                                        <option value="PR03">GRADE 3</option>
                                                                        <option value="PR05">GRADE 5</option>
                                                                        <option value="NUR01">NURSERY 1</option>
                                                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="">Picture</label>
                                <input type="file" class="form-control form-control-sm" name="image">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-light btn-sm" form="rec-book-form" type="reset">Reset</button>
                <button class="btn btn-primary btn-sm" form="rec-book-form" type="submit" name="submit"> <i
                        class=""></i> Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection
