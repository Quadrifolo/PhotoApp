<template>
    <div>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group files text-center" ref="fileform">
                <input type="file" ref="file" multiple="multiple">
                <span id ="val"></span>
                    <a class = "btn btn-secondary" @click.prevent="submitFiles()">Upload</a>                
                </input>


            </div>

        </form>
        <progress max="100" style="width: 100%;":value.prop="uploadPercentage" v-if="uploading"></progress>

        <div class="container">
            <hr class="mt-2 mb-5">
            <div class="row text-center text-lg-left">
                <div class="col-lg-3 col-md-4 col-6" v-for="(image,index) in images" :key="index">
                    <a href="">
                        <img :src="'/images/'+image.image" class="img-fluid img-thumbnail">
                            <button class="btn btn-danger btn-sm" @click.prevent="DeleteImage(image.id)">Delete </button>

                    </a>


                </div>
                
                 </div>



        </div>

    </div>
</template>

<script>
export default {
    props:['album_id'],
    data(){
        return{
            uploadPercentage:'',
            uploading:false,
            images:[]

        }
    },
    mounted(){
        this.getImage()
    },

    methods:{
        submitFiles(){
        // Function which allows for multiple images to be held at the same time 
            let formData = new FormData();
            for(var i=0;i<this.$refs.file.files.length;i++){
                let file = this.$refs.file.files[i];
                formData.append('files['+i+']',file);
                formData.append('album_id',this.album_id);
            }
            this.uploading = true;
            this.$refs.file.value=''
// Action to post upload images function in GalleryController 
            axios.post('/uploadImage',formData,{

                header:{
                    "content-type":"multipart/form-data"
                },
                // Function For Status Bar 
                onUploadProgress:function(progressEvent){

                    // Coverts upload percentage into integer and receives it as a whole numbe r
                    this.uploadPercentage = parseInt(Math.round((
                        progressEvent.loaded*100)/progressEvent.total));
                }.bind(this)
                    
                

            }).then((response)=>{
                this.getImage()

            })
        },
        // Retrieves images for album
        getImage(){
            axios.get('/getImages').then((response)=>{
                // Data Based on Get Request 
                this.images = response.data

            }).catch((error)=>{
                alert('error')
            })
        },
       
            
        DeleteImage(id){
								Swal.fire({
						  title: 'Are you sure?',
						  icon: 'warning',
						  text:"You won't be able to revert this!",
						  showCancelButton:true,
						  confirmButtonColor:'#d33',
						  cancelButtonColor:'#3085d6',
						  confirmButtonText:'Yes,delete it'


				}).then((result)=>{

					if(result.value){
						axios.delete('/image/'+id).then((response)=>{
							this.getImage()
							Swal.fire({
						  	position: 'center',
						  	icon: 'success',
						 	title: 'Your chnages has been saved',
						 	showConfirmButton: false,
						  	timer: 1500
							})
							
						}).catch((error)=>{
					console.log(error)
				})
					}

				})
			}

    }
}
</script>