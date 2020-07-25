<template>
    <div class="container">
        <div class="row mt-3 ml-1">
            <h2>Client Details</h2>
        </div>
        <!-- INDEX VIEW -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title mt-2"></h3>
                  <button class="btn btn-success xs" id="add_btn" @click="AddClientModal">
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
                      <th>Contact</th>
                      <th>Created At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="client in clients.data">
                      <td>{{client.id}}</td>
                      <td>{{client.name}}</td>
                      <td>{{client.contact}}</td>
                      <td>{{client.created_at | myDate}}</td>
                      <td>
                          <a href="#" @click="EditClientModal(client)">
                            <i class="fas fa-edit blue ml-1"></i>
                          </a>
                          <a href="#" @click="deleteClient(client.id)"><i class="fas fa-trash red ml-1"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="clients" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- CREATE/EDIT VIEW -->
        <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="clientModalLabel">Add New Client</h5>
                <h5 v-show="editmode" class="modal-title" id="clientModalLabel">Update Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateClient() : createClient()">
              <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" id="name" type="text" name="name" placeholder="Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.contact" id="contact" type="text" name="contact" placeholder="Contact" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('contact') }">
                  <has-error :form="form" field="contact"></has-error>
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
            clients:{},
            form: new Form({
              id:       '',
              name:     '',
              contact:    '',
            })
          }
        },
        methods:{
          getResults(page = 1) {
            let query = this.search;
            axios.get('garment/api/findclient?q='+query+'&page=' + page)
              .then(response => {
                this.clients = response.data;
            });
          },
          AddClientModal(){
            this.form.reset();
            this.editmode = false;
            $('#clientModal').modal('show');
          },
          EditClientModal(client){
            this.form.fill(client);
            this.editmode = true;
            $('#clientModal').modal('show');
          },
          deleteClient(id){
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
                  this.form.delete('garment/api/client/'+id);
                  Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  );
                  Fire.$emit('searching');
                }
            });
          },
          loadClient(){
            axios.get('garment/api/client').then(({data})=>(this.clients = data))
          },
          createClient(){
            this.$Progress.start();
            this.form.post('garment/api/client')
            .then(()=>{
              Fire.$emit('RefreshTable');
              $('#clientModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Client Created Successfully'
              });
              this.$Progress.finish();
            })
            .catch(()=>{});
          },
          updateClient(){
            this.$Progress.start();
            this.form.put('garment/api/client/'+this.form.id)
            .then(()=>{
              Fire.$emit('searching');
              $('#clientModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Client Updated Successfully'
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
            this.loadClient();
            Fire.$on('searching',()=>{
              let query = this.search;
              axios.get('garment/api/findclient?q='+query).then(({data})=>(this.clients = data))
            });
            Fire.$on('RefreshTable',()=>this.loadClient());
        }
    };
</script>
