<template>
    <div class="container">
        <div class="row mt-3 ml-1">
            <h2>Product Details</h2>
        </div>
        <!-- INDEX VIEW -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title mt-2"></h3>
                  <button class="btn btn-success xs" id="add_btn" @click="AddProductModal">
                      <i class="fas fa-user-plus fa-lg"></i>
                  </button>
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
                      <th>Name</th>
                      <th>Price</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="product in products.data">
                      <td>{{product.id}}</td>
                      <td>{{product.name}}</td>
                      <td>{{product.price}}</td>
                      <td>{{product.created_at | myDate}}</td>
                      <td>
                          <a href="#" @click="EditProductModal(product)">
                            <i class="fas fa-edit blue ml-1"></i>
                          </a>
                          <a href="#" @click="deleteProduct(product.id)"><i class="fas fa-trash red ml-1"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="products" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- CREATE/EDIT VIEW -->
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="productModalLabel">Add New Product</h5>
                <h5 v-show="editmode" class="modal-title" id="productModalLabel">Update Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateProduct() : createProduct()">
              <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" id="name" type="text" name="name" placeholder="Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.price" id="price" type="text" name="price" placeholder="Price"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                  <has-error :form="form" field="price"></has-error>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                <button v-show="editmode" type="submit" class="btn btn-primary">Update</button>
              </div>
              </form>
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
            products:{},
            form: new Form({
              id:       '',
              name:     '',
              price: '',
            })
          }
        },
        methods:{
          getResults(page = 1) {
            let query = this.search;
            axios.get('garment/api/findproduct?q='+query+'&page=' + page)
              .then(response => {
                this.products = response.data;
            });
          },
          AddProductModal(){
            this.form.reset();
            this.editmode = false;
            $('#productModal').modal('show');
          },
          EditProductModal(product){
            this.form.fill(product);
            this.editmode = true;
            $('#productModal').modal('show');
          },
          deleteProduct(id){
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
                  this.form.delete('garment/api/product/'+id);
                  Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  );
                  Fire.$emit('searching');
                }
            });
          },
          loadProduct(){
            axios.get('garment/api/product').then(({data})=>(this.products = data))
          },
          createProduct(){
            this.$Progress.start();
            this.form.post('garment/api/product')
            .then(()=>{
              Fire.$emit('RefreshTable');
              $('#productModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Product Created Successfully'
              });
              this.$Progress.finish();
            })
            .catch(()=>{});
          },
          updateProduct(){
            this.$Progress.start();
            this.form.put('garment/api/product/'+this.form.id)
            .then(()=>{
              Fire.$emit('searching');
              $('#productModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Product Updated Successfully'
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
            this.loadProduct();
            Fire.$on('searching',()=>{
              let query = this.search;
              axios.get('garment/api/findproduct?q='+query).then(({data})=>(this.products = data))
            });
            Fire.$on('RefreshTable',()=>this.loadProduct());
        }
    };
</script>
