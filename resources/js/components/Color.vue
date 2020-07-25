<template>
    <div class="container">
        <div class="row mt-3 ml-1">
            <h2>Color Details</h2>
        </div>
        <!-- INDEX VIEW -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title mt-2"></h3>
                  <button class="btn btn-success xs" id="add_btn" @click="AddColorModal">
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
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="color in colors.data">
                      <td>{{color.id}}</td>
                      <td>{{color.name}}</td>
                      <td>
                          <a href="#" @click="EditColorModal(color)">
                            <i class="fas fa-edit blue ml-1"></i>
                          </a>
                          <a href="#" @click="deleteColor(color.id)"><i class="fas fa-trash red ml-1"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="colors" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- CREATE/EDIT VIEW -->
        <div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editmode" class="modal-title" id="colorModalLabel">Add New Color</h5>
                <h5 v-show="editmode" class="modal-title" id="colorModalLabel">Update Color</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateColor() : createColor()">
              <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" id="name" type="text" name="name" placeholder="Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
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
            colors:{},
            form: new Form({
              id:       '',
              name:     '',
            })
          }
        },
        methods:{
          getResults(page = 1) {
            let query = this.search;
            axios.get('garment/api/findcolor?q='+query+'&page=' + page)
              .then(response => {
                this.colors = response.data;
            });
          },
          AddColorModal(){
            this.form.reset();
            this.editmode = false;
            $('#colorModal').modal('show');
          },
          EditColorModal(color){
            this.form.fill(color);
            this.editmode = true;
            $('#colorModal').modal('show');
          },
          deleteColor(id){
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
                  this.form.delete('garment/api/color/'+id);
                  Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  );
                  Fire.$emit('searching');
                }
            });
          },
          loadColor(){
            axios.get('garment/api/color').then(({data})=>(this.colors = data))
          },
          createColor(){
            this.$Progress.start();
            this.form.post('garment/api/color')
            .then(()=>{
              Fire.$emit('RefreshTable');
              $('#colorModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Color Created Successfully'
              });
              this.$Progress.finish();
            })
            .catch(()=>{});
          },
          updateColor(){
            this.$Progress.start();
            this.form.put('garment/api/color/'+this.form.id)
            .then(()=>{
              Fire.$emit('searching');
              $('#colorModal').modal('hide');
              Toast.fire({
                icon: 'success',
                title: 'Color Updated Successfully'
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
            this.loadColor();
            Fire.$on('searching',()=>{
              let query = this.search;
              axios.get('garment/api/findcolor?q='+query).then(({data})=>(this.colors = data))
            });
            Fire.$on('RefreshTable',()=>this.loadColor());
        }
    };
</script>
