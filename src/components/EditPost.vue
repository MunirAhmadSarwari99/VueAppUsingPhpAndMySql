<template>
  <h4>Edit Post {{ image }}</h4>
  <div class="row">
    <div class="col-md-3 mb-3">
      <img id="ImageView" v-bind:src="'./src/assets/default.png'" class="img-thumbnail w-75" alt="...">
    </div>
    <div class="col-md-12 mb-3">
      <div class="form-floating">
        <input type="file" class="form-control" id="image" ref="file" @change="getImageFromInput()">
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="title" v-model="title" placeholder="Title">
        <label for="title">Title</label>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-floating">
        <textarea class="form-control" id="details"  v-model="details" placeholder="Details"></textarea>
        <label for="details">Details</label>
      </div>
    </div>
    <div class="col-md-12 mt-4">
      <div class="form-floating">
        <RouterLink class="btn btn-dark  text-white float-start" to="/">Back</RouterLink>
        <button type="button" class="btn btn-success float-end" @click="addData()">Create</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "NewPost",
  data(){
    return{
      cart: [],
      image: null,
      title: null,
      details: null,
    }
  },
  mounted() {
    this.fetchData('http://localhost/EcommerceVue/src/API/api.php?action=editCart');
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
    addData(){
      this.image = this.$refs.file.files[0];
      var  data = new FormData();
      data.append('image', this.image);
      data.append('title', this.title);
      data.append('details', this.details);
      axios.post('http://localhost/EcommerceVue/src/API/api.php?action=AddCart', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then((res) =>{
            this.fetchData('http://localhost/EcommerceVue/src/API/api.php?action=fetchData');
          }).catch((res) =>{
        console.log("Error");
      });
    },
    fetchData(API){
      fetch(API)
          .then((res) => { return res.json() })
          .then((res) => {
            this.isloading = false;
            this.cart = res;
          });
    }
  }
}
</script>

<style scoped>
#details{
  height: 15rem;
}
</style>