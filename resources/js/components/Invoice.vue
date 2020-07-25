<template>
    <div class="container">
        <div class="row mt-3 ml-1">
            <h2>Invoice Details</h2>
        </div>
        <!-- INDEX VIEW -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title mt-2"></h3>
                  <router-link to="/transaction">
                  <button class="btn btn-success xs" id="add_btn">
                      <i class="fas fa-user-plus fa-lg"></i>
                  </button>
                  </router-link>
                <div class="card-tools mt-2">
                  <div class="input-group input-group-sm">
                    <input v-model="search" type="text" name="table_search" class="form-control float-right" id="table_search" placeholder="Search" @keyup.enter="searchit">
                    <div class="input-group-append ml-2">
                      <button class="btn btn-primary" id="s_btn" @click="searchit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>

                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Client Name</th>
                      <th>Price</th>
                      <th>Discount</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="invoice in invoices.data">
                      <td>{{invoice.id}}</td>
                      <td>{{invoice.client.name}}</td>
                      <td>{{invoice.price}}</td>
                      <td>{{invoice.discount}}</td>
                      <td>{{invoice.created_at | myDate}}</td>
                      <td>
                          <a href="#" @click="DetailInvoiceModal(invoice)">
                            <i class="fas fa-eye blue ml-1"></i>
                          </a>
                          <a href="#" @click="deleteInvoice(invoice.id)"><i class="fas fa-trash red ml-1"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="invoices" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- detail view -->
        <div class="modal fade" id="ABC" tabindex="-1" role="dialog" aria-labelledby="invoiceModalDetailLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalDetailLabel">Invoice Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                <h3 class="profile-username text-center" id="title"></h3>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <h5><b>Client Name: </b> <a id="client_name" class="float-right"></a></h5>
                    <br>
                    <h5><b>Price: </b> <a id="price" class="float-right"></a></h5>
                    <br>
                    <h5><b>Discount: </b> <a id="discount" class="float-right"></a></h5>
                    <br>
                    <h5><b>Date: </b> <a id="date" class="float-right"></a></h5>
                  </li>
                </ul>
                
                <b>Items</b>
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Product Name</th>
                      <th>Color</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Discount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="child_transaction in child_transactions">
                      <td>{{child_transaction.product.name}}</td>
                      <td>{{child_transaction.color_id}}</td>
                      <td>{{child_transaction.quantity}}</td>
                      <td>{{child_transaction.price}}</td>
                      <td>{{child_transaction.discount}}</td>
                    </tr>
                  </tbody>
                </table>
              <!-- /.card-body -->
              </div>
            </div>
          </div>
        </div>

        <!-- INVOICE -->
        <div class="modal fade" id="invoiceModalDetail" tabindex="-1" role="dialog" aria-labelledby="invoiceModalDetailLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
              <div class="modal-header float-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="close" data-dismiss="modal"><i class="fas fa-times-circle fa-lg mt-1 red"></i></button>
              </div>
              <div class="modal-body">
                <div id="invoice">
                  <div class="invoice overflow-auto">
                      <div style="min-width: 600px">
                          <header>
                              <div class="row">
                                  <div class="col">
                                      <a target="_blank" href="https://lobianijs.com">
                                          <img src="http://lobianijs.com/lobiadmin/version/1.0/ajax/img/logo/lobiadmin-logo-text-64.png" data-holder-rendered="true" />
                                          </a>
                                  </div>
                                  <div class="col company-details">
                                      <h2 class="name">
                                          <a target="_blank" href="https://lobianijs.com">
                                          Current User
                                          </a>
                                      </h2>
                                  </div>
                              </div>
                          </header>
                          <main>
                              <div class="row contacts">
                                  <div class="col invoice-to">
                                      <div class="text-gray-light">INVOICE TO:</div>
                                      <h2 class="to">Client name</h2>
                                      <div class="address">Contact</div>
                                  </div>
                                  <div class="col invoice-details">
                                      <h1 class="invoice-id">INVOICE ID</h1>
                                      <div class="date">Date of Invoice: 01/10/2018</div>
                                  </div>
                              </div>
                              <table border="0" cellspacing="0" cellpadding="0">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th class="text-left">PRODUCT</th>
                                          <th class="text-right">COLOR</th>
                                          <th class="text-right">QUANTITY</th>
                                          <th class="text-right">BASE PRICE</th>
                                          <th class="text-right">DISCOUNT</th>
                                          <th class="text-right">TOTAL</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td class="no">01</td>
                                          <td class="text-left"><h3>J.</h3></td>
                                          <td class="qty">Blue</td>
                                          <td class="qty">4</td>
                                          <td class="unit">$0.00</td>
                                          <td class="unit">$0.00</td>
                                          <td class="total">$0.00</td>
                                      </tr>
                                      <tr>
                                          <td class="no">02</td>
                                          <td class="text-left"><h3>Sana Safinaz</h3></td>
                                          <td class="qty">Red</td>
                                          <td class="qty">6</td>
                                          <td class="unit">$0.00</td>
                                          <td class="unit">$0.00</td>
                                          <td class="total">$0.00</td>
                                      </tr>
                                  </tbody>
                                  <tfoot>
                                      <tr>
                                          <td colspan="3"></td>
                                          <td colspan="3">SUBTOTAL</td>
                                          <td>$5,200.00</td>
                                      </tr>
                                      <tr>
                                          <td colspan="3"></td>
                                          <td colspan="3">Discount</td>
                                          <td>$1,300.00</td>
                                      </tr>
                                      <tr>
                                          <td colspan="3"></td>
                                          <td colspan="3">GRAND TOTAL</td>
                                          <td>$6,500.00</td>
                                      </tr>
                                  </tfoot>
                              </table>
                              <div class="thanks">Thank you!</div>
                          </main>
                          <footer>
                              Copyright C2P. All rights reserved.
                          </footer>
                      </div>
                      <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                      <div></div>
                  </div>
              </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return{
            search:'',
            editmode: false,
            invoices:{},
            child_transactions:{},
            form: new Form({
              id:       '',
              name:     '',
            })
          }
        },
        methods:{
          getResults(page = 1) {
            let query = this.search;
            axios.get('garment/api/findinvoice?q='+query+'&page=' + page)
              .then(response => {
                this.invoices = response.data;
            });
          },
          AddInvoiceModal(){
            this.form.reset();
            this.editmode = false;
            $('#invoiceModal').modal('show');
          },
          EditInvoiceModal(invoice){
            this.form.fill(invoice);
            this.editmode = true;
            $('#invoiceModal').modal('show');
          },
          DetailInvoiceModal(invoice)
          {
            $('#invoiceModalDetail #children li').remove();
            $('#invoiceModalDetail #client_name').text(invoice.client.name);
            $('#invoiceModalDetail #price').text(invoice.price);
            $('#invoiceModalDetail #discount').text(invoice.discount);
            $('#invoiceModalDetail #date').text(invoice.created_at);
            this.child_transactions = invoice.child_transactions;
            // for(var i = 0; i < invoice.child_transactions.length; i++ ){
            //   // $('#invoiceModalDetail #children').append('<li>'+invoice.child_transactions[i].price+'</li>');
            // }
            // $('#invoiceModalDetail').modal('show');
            this.$router.push({name:'output',params:{id:invoice.id}});
          },
          deleteInvoice(id){
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                  this.form.delete('garment/api/master_transaction/'+id);
                  Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  );
                  Fire.$emit('searching');
                }
            });
          },
          loadInvoice(){
            axios.get('garment/api/master_transaction').then(({data})=>(this.invoices = data))
          },
          createInvoice(){
            this.$Progress.start();
            this.form.post('garment/api/invoice')
            .then(()=>{
              Fire.$emit('RefreshTable');
              $('#invoiceModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Invoice Created Successfully'
              });
              this.$Progress.finish();
            })
            .catch(()=>{});
          },
          updateInvoice(){
            this.$Progress.start();
            this.form.put('garment/api/invoice/'+this.form.id)
            .then(()=>{
              Fire.$emit('searching');
              $('#invoiceModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Invoice Updated Successfully'
              });
              this.$Progress.finish();
            })
            .catch(()=>{});
          },
          searchit(){
            Fire.$emit('searching');
      }
        },
        mounted() {
            this.loadInvoice();
            Fire.$on('searching',()=>{
              let query = this.search;
              axios.get('garment/api/findinvoice?q='+query).then(({data})=>(this.invoices = data))
            });
            Fire.$on('RefreshTable',()=>this.loadInvoice());
        }
    };
</script>
