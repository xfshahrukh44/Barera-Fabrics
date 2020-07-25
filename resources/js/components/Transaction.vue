<template>
    <div class="container">
        <div class="card card-primary mt-3">
              <div class="card-header">
                <h3 class="card-title">Invoice</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <!-- MASTER TRANSACTION -->
              <form role="form" @submit.prevent="">
                <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-3">
                        <label>Client</label>
                        <a href="#" class="float-right" @click="AddClientModal"><span class="fas fa-plus"></span></a>
                        <select v-model="form.client_id" id="client_id" name="client_id" class="form-control" :class="{ 'is-invalid': form.errors.has('client_id') }" @change="get_client_data">
                          <option value="">Select Client</option>
                          <option v-for="client in clients" :value="client.id">{{client.name}}</option>
                        </select>
                      </div>
                      <div class="form-group col-md-3">
                        <label>Contact</label>
                        <input v-model="form.client_contact" id="client_contact" type="text" name="client_contact" placeholder="Contact"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('client_contact') }" readonly>
                        <has-error :form="form" field="client_contact"></has-error>
                      </div>
                      <div class="form-group col-md-2">
                        <label>Price</label>
                        <input v-model="form.price" id="price" type="text" name="price" placeholder="Enter Quantity"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('price') }" readonly>
                        <has-error :form="form" field="price"></has-error>
                      </div>
                      <div class="form-group col-md-2">
                        <label>Discount</label>
                        <input v-model="form.discount" id="discount" type="text" name="discount" placeholder="Discount"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('discount') }" @keyup="mainDiscount">
                        <has-error :form="form" field="discount"></has-error>
                      </div>
                      <div class="form-group col-md-2">
                        <label>Total</label>
                        <input v-model="form.total" id="total" type="text" name="total" placeholder="Total"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('total') }" readonly>
                        <has-error :form="form" field="total"></has-error>
                      </div>
                    </div>

                    <div class="" style="text-align:right;" >
                      <button class="btn btn-primary col-md-12" @click="showChild">Add Invoice Details</button>
                    </div>
                </div>
                <!-- /.card-body -->
              </form>

              <!-- CHILD TRANSACTIONS -->
              <div class="child">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <label>Product</label>
                      <a href="#" class="float-right" @click="AddProductModal"><span class="fas fa-plus"></span></a>
                    </div>
                    <div class="col-md-2">
                      <label>Color</label>
                      <a href="#" class="float-right" @click="AddColorModal"><span class="fas fa-plus"></span></a>
                    </div>
                    <div class="col-md-1">
                      <label>Quantity</label>
                    </div>
                    <div class="col-md-2">
                      <label>Price</label>
                    </div>
                    <div class="col-md-1">
                      <label>Discount</label>
                    </div>
                    <div class="col-md-2">
                      <label>Total</label>
                    </div>
                    <div class="col-md-1">
                      <label></label>
                    </div>
                  </div>
                  <div class="row" v-for="(childs, index) in child">
                    <div class="form-group col-md-3">
                      <select v-model="childs.product_id" id="product_id" name="product_id" class="form-control" @change="get_product_data(index)">
                        <option value="">Select Product</option>
                        <option v-for="product in products" :value="product.id">{{product.name}}</option>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <select v-model="childs.color_id" id="color_id" name="color_id" class="form-control">
                        <option value="">Select Color</option>
                        <option v-for="color in colors" :value="color.id">{{color.name}}</option>
                      </select>
                    </div>
                    <div class="form-group col-md-1">
                      <input v-model="childs.quantity" id="quantity" type="text" name="quantity" placeholder="Enter Quantity"
                      class="form-control" @keyup="updateChildPrice(index), updatePrice(index)">
                    </div>
                    <div class="form-group col-md-2">
                      <input v-model="childs.price" id="price" type="text" name="price" placeholder="Enter Price"
                      class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-1">
                      <input v-model="childs.discount" id="discount" type="text" name="discount" placeholder="Enter Discount"
                      class="form-control" @keyup="updateChildPrice(index), updatePrice(index)">
                    </div>
                    <div class="form-group col-md-2">
                      <input v-model="childs.total" id="total" type="text" name="total" placeholder="Enter Discount"
                      class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-1">
                      <a href="#" class="" @click="deleteChild(index)"><i class="fas fa-minus-circle red"></i></a>
                      <a href="#" class="" @click="addChild"><span class="fas fa-plus-circle green"></span></a>
                      <!-- <button class="btn btn-info" @click="addChild"><span class="fas fa-plus"></span></button>
                      <button class="btn btn-danger" @click="deleteChild(index)"><span class="fas fa-minus"></span></button> -->
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button class="btn btn-primary" @click="submit">Submit</button>
                </div>
              </div>

              <!-- CLIENT CREATE VIEW -->
              <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="clientModalLabel">Add New Client</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form @submit.prevent="createClient()">
                    <div class="modal-body">
                      <div class="form-group">
                        <input v-model="client_form.name" id="name" type="text" name="name" placeholder="Name"
                          class="form-control" :class="{ 'is-invalid': client_form.errors.has('name') }">
                        <has-error :form="client_form" field="name"></has-error>
                      </div>
                      <div class="form-group">
                        <input v-model="client_form.contact" id="contact" type="text" name="contact" placeholder="Contact" 
                          class="form-control" :class="{ 'is-invalid': client_form.errors.has('contact') }">
                        <has-error :form="client_form" field="contact"></has-error>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- PRODUCT CREATE VIEW -->
              <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form @submit.prevent="createProduct()">
                    <div class="modal-body">
                      <div class="form-group">
                        <input v-model="product_form.name" id="name" type="text" name="name" placeholder="Name"
                          class="form-control" :class="{ 'is-invalid': product_form.errors.has('name') }">
                        <has-error :form="product_form" field="name"></has-error>
                      </div>
                      <div class="form-group">
                        <input v-model="product_form.price" id="price" type="text" name="price" placeholder="Price"
                          class="form-control" :class="{ 'is-invalid': product_form.errors.has('price') }">
                        <has-error :form="product_form" field="price"></has-error>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- COLOR CREATE VIEW -->
              <div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="colorModalLabel">Add New Color</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form @submit.prevent="createColor()">
                    <div class="modal-body">
                      <div class="form-group">
                        <input v-model="color_form.name" id="name" type="text" name="name" placeholder="Name"
                          class="form-control" :class="{ 'is-invalid': color_form.errors.has('name') }">
                        <has-error :form="color_form" field="name"></has-error>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                    </form>
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
            count:0,
            master_id:{},
            products:{},
            clients:{},
            colors:{},
            child:[
              {
                product_id:'',
                master_transaction_id:0,
                quantity:0,
                color_id:'',
                price:0,
                discount:0,
                total: 0,
              }
            ],
            form: new Form({
              client_id: '',
              price:     0,
              discount:  0,
              total:  0,
              client_contact: '',
            }),
            client_form: new Form(
              {
                id:       '',
                name:     '',
                contact:    '',
              }
            ),
            product_form: new Form({
              id:       '',
              name:     '',
              price: '',
            }),
            color_form: new Form({
              id:       '',
              name:     '',
            }),
          }
        },
        methods:{
            loadProduct(){
                axios.get('garment/api/get_product').then(({data})=>(this.products = data))
            },
            loadClients(){
                axios.get('garment/api/get_clients').then(({data})=>(this.clients = data))
            },
            loadColors(){
                axios.get('garment/api/get_colors').then(({data})=>(this.colors = data))
            },
            showChild(){
               this.form.post('garment/api/master_transaction').then(({data})=>{
                this.master_id = data;
                console.log(this.master_id);
                this.child[0].master_transaction_id = this.master_id;
                }
                );
              $('.child').show();
              this.count++;
            },
            addChild(){
              this.count++;
              this.child.push({
                product_id:'',
                master_transaction_id: this.master_id,
                quantity:0,
                color_id:'',
                price:0,
                discount:0,
                total: 0,
              });
            },
            deleteChild(index){
              this.count--;
              this.child.splice(index,1);
              this.updatePrice(index);
            },
            submit(){
              this.$Progress.start();
              axios({
                url: 'garment/api/child_transaction?count='+this.count,
                method: 'post',
                data: this.child
              }).then(()=>{
                Toast.fire({
                icon: 'success',
                title: 'Transaction Successfull'
                });
                this.$Progress.finish();
              }).then(()=>{
                this.form.put('garment/api/master_transaction/' + this.master_id);
                this.form.reset();
                this.child[0].product_id = '';
                this.child[0].master_transaction_id = '';
                this.child[0].quantity = '';
                this.child[0].color_id = '';
                this.child[0].price = '';
                this.child[0].total = 0;
                this.child[0].discount = '';
                $('.child').hide();
                this.$router.push({name:'output', params: {id: this.master_id}});
              });
            },
            get_client_data(){
              axios.get('garment/api/get_client_data?q='+this.form.client_id).then(({data})=>(this.form.client_contact = data));
            },
            get_product_data(index)
            {
              axios.get('garment/api/get_product_data?q='+this.child[index].product_id).then(({data}) => 
              {
                this.child[index].price = data;
              });
            },
            updatePrice(index)
            {
              // this.child[index].discount = 0;
              this.form.price = 0;
              this.form.total = 0;
              for(var i = 0; i < this.child.length; i++)
              {
                this.form.price += parseInt(this.child[i].total);
                this.form.total += parseInt(this.child[i].total);
              }
            },
            updateChildPrice(index)
            {
              if(this.child[index].discount <= this.child[index].total){
                this.child[index].total = (this.child[index].quantity * this.child[index].price) - this.child[index].discount;
              }
            },
            mainDiscount()
            {
              this.form.total = parseInt(this.form.price) - parseInt(this.form.discount);
            },
            AddClientModal(){
              this.client_form.reset();
              this.editmode = false;
              $('#clientModal').modal('show');
            },
            createClient(){
              this.$Progress.start();
              this.client_form.post('garment/api/client')
              .then(()=>{
                Fire.$emit('RefreshTable');
                $('#clientModal').modal('hide');
                Toast.fire({
                  icon: 'success',
                  title: 'Client Created Successfully'
                });
                this.$Progress.finish();
		            this.loadClients();
              })
              .catch(()=>{});
            },
            AddProductModal(){
              this.product_form.reset();
              this.editmode = false;
              $('#productModal').modal('show');
            },
            createProduct(){
              this.$Progress.start();
              this.product_form.post('garment/api/product')
              .then(()=>{
                Fire.$emit('RefreshTable');
                $('#productModal').modal('hide');
                Toast.fire({
                  icon: 'success',
                  title: 'Product Created Successfully'
                });
                this.$Progress.finish();
                this.loadProduct();
              })
              .catch(()=>{});
            },
            AddColorModal(){
              this.color_form.reset();
              this.editmode = false;
              $('#colorModal').modal('show');
            },
            createColor(){
              this.$Progress.start();
              this.color_form.post('garment/api/color')
              .then(()=>{
                Fire.$emit('RefreshTable');
                $('#colorModal').modal('hide');
                Toast.fire({
                  icon: 'success',
                  title: 'Color Created Successfully'
                });
                this.$Progress.finish();
                this.loadColors();
              })
              .catch(()=>{});
            },
        },
        mounted() {
            this.loadProduct();
            this.loadClients();
            this.loadColors();
            $('.child').hide();
        }
    };
</script>
