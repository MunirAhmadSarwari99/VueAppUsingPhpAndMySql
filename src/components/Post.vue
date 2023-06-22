<template>
  <div class="row">
    <div class="col-md-12 mb-3">
      <RouterLink class="btn btn-dark  text-white float-end" to="/NewPost">New</RouterLink>
    </div>
    <div class="col-md-3" v-for="key in cart">
      <div class="card">
        <img v-if="key.image == 'default.png'" v-bind:src="'./src/assets/' + key.image" class="img-thumbnail" v-bind:alt="key.title">
        <img v-if="key.image != 'default.png'" v-bind:src="'./src/assets/uploads/' + key.image" class="img-thumbnail" v-bind:alt="key.title">
        <div class="card-body">
          <h5 class="card-title">{{ key.title }}</h5>
          <p class="card-text">{{ key.details }}</p>
        </div>
        <div class="card-body">
          <button class="btn btn-danger text-light float-start" @click="deleteCart(key.id)">Delete</button>
          <button class="btn btn-success text-light float-end" @click="EditCart(key.id)" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
        </div>
      </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="edit" data-bs-backdrop="static"  tabindex="-1" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog  modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="edit">Edit Post {{ id}}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-3 mb-3">
                <img id="ImageView" v-if="image == 'default.png'" v-bind:src="'./src/assets/' + image" class="img-thumbnail w-75 ImageView" v-bind:alt="title">
                <img id="ImageView" v-if="image != 'default.png'" v-bind:src="'./src/assets/uploads/' + image" class="img-thumbnail w-75 ImageView" v-bind:alt="title">
              </div>
              <div class="col-md-12 mb-3">
                <div class="form-floating">
                  <input type="file" class="form-control" id="image" ref="file" @change="onFileSelected">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="title" v-model="title"  placeholder="Title">
                  <label for="title">Title</label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating">
                  <textarea class="form-control" id="details" ref="details" v-model="details"  placeholder="Details"></textarea>
                  <label for="details">Details</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="updateCart(id)">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Post",
  data(){
    return {
      cart: [],
      id: null,
      image: null,
      title: null,
      details: null,
    }
  },
  methods:{
    getImageFromInput(){
      var forminput = document.getElementById('image');
      var fr = new FileReader();
      fr.readAsDataURL(forminput.files[0]);
      fr.onloadend = function (e) {
        var bin = e.target.result;
        var img = document.getElementById('ImageView');
        img.src = bin;
      }
    },
    fetchData(API){
      axios.post(API)
          .then((res) =>{
            this.cart = res.data;
          }).catch((res) =>{});
    },
    EditCart(id){
      var  data = new FormData();
      data.append('id', id);

      axios.post('http://localhost/EcommerceVue/src/API/api.php?action=editCart', data)
          .then((res) =>{
            res.data.forEach((item) => {
              this.id = item.id;
              this.title = item.title;
              this.details = item.details;
              this.image = item.image;
            })
          }).catch((res) =>{});

    },
    onFileSelected(event){
      var forminput = document.getElementById('image');
      var fr = new FileReader();
      fr.readAsDataURL(forminput.files[0]);
      fr.onloadend = function (e) {
        var bin = e.target.result;
        var img = document.getElementById('ImageView');
        img.src = bin;
      }
      this.image = event.target.files[0];
    },
    updateCart(id){
      var  data = new FormData();
      data.append('id', this.id);
      data.append('image', this.image);
      data.append('title', this.title);
      data.append('details', this.details);
      axios.post('http://localhost/EcommerceVue/src/API/api.php?action=updateCart', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then((res) =>{
        console.log("Success");
        document.documentElement.querySelector(".modal.fade.show .btn-close").click();
        this.fetchData('http://localhost/EcommerceVue/src/API/api.php?action=fetchData');
      }).catch((res) =>{
        console.log("Error");
      });
    },
    deleteCart(id){
      var  data = new FormData();
      data.append('id', id);

      axios.post('http://localhost/EcommerceVue/src/API/api.php?action=deleteCart', data)
          .then((res) =>{
            this.fetchData('http://localhost/EcommerceVue/src/API/api.php?action=fetchData');
          }).catch((res) =>{
      });
    }
  },
  created() {
    this.fetchData('http://localhost/EcommerceVue/src/API/api.php?action=fetchData');
  }
}
</script>

<style scoped>
#details{
  height: 15rem;
}
</style>