<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SuccessButton from '@/Components/SuccessButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import WarningButton from '@/Components/WarningButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head,useForm } from '@inertiajs/vue3';
import { nextTick,ref} from 'vue';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

const nameInput = ref(null);
const modal = ref(false);
const title = ref('');
const operation = ref(1);
const id = ref('');
let imglink='';

const props = defineProps({
    client:{type:Object},
    clients:{type:Object},
    images:{type:Object},
   // departments:{type:Object}
});


//declaracion de las variables del formulario
const form = useForm({
    client_id:props.client.id,
    name:props.client.name,
    apellidos:props.client.apellidos,
    telefono:props.client.telefono,
    observacion:props.client.observacion,

    id:'',
    imagen:'',
    descripcion:'',
    
});

const formPage = useForm({}); //para la paginacion
const onPageClick = (event)=>{
    formPage.get(route('clients.index',{page:event}));
}
//Abre el modal y carga los dats si se va actualizar
const openModal = (op,imagen,descripcion,image)=>{
    modal.value = true;
    nextTick(()=>nameInput.value.focus());
    operation.value=op;
    id.value=image;
    if(op==1){
        title.value='Cargar Imagen';
    }
    else{
        title.value='Actualizar Imagen';
        form.imagen=imagen;
        form.descripcion=descripcion;
        form.id=image;
    }
}
//cierra modal
const closeModal=()=>{
    modal.value=false;
    form.reset();
}
//Registrar y actualizar
const save=()=>{
    if(operation.value==1){
        form.post(route('images.store'),{
            onSuccess: ()=>{ok('Imagen cargada')}
        });
    }
    else{
        form.put(route('images.update',id.value),{
            onSuccess: ()=>{ok('Actualizado ')}
        });
    }
}
//Muestra mensaje de confirmacion y cierra modal
const ok=(msj)=>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'success'})
}
//Eliminar
const deleteImagen = (id,name) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'Esta seguro de eliminar la fotografia "'+name+' " ?',
        icon:'question', showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check"></i> Yes, delete',
        cancelButtonText:'<i class="fa-solid fa-ban"></i> Cancel'
    }).then((result)=>{
        if(result.isConfirmed){
            /*form.delete(route('employees.destroy',id,{
                onSuccess:()=>{ ok('Employee deleted') }
            }));*/
            form.delete(route('images.destroy',id),{
            onSuccess: ()=>{ok('Fotografia eliminada')}
        });
        }
    });
}

const zoomImagen=(imagen)=>{
    imglink=imagen;

//console.log(imglink);
    document.getElementById("imgview").src="/storage/"+imglink;
    var imgsrc = document.getElementById("imagen1");
    imgsrc.style.transform = 'scale(1)';
   

}
//zoomImagen('upload-imgclients/WYe8SLcv1fZP3EUF6y2eNxdl13JCL2a7O4yQy5JZ.jpg');
const cerrarImg=()=>{
    var imgsrc = document.getElementById("imagen1");
    imgsrc.style.transform = 'scale(0)';
}
</script>

<template>
    <Head title="Clientes perfil" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ form.name+' '+form.apellidos }}</h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <PrimaryButton @click="$event=>openModal(1)">
                        <i class="fa-solid fa-plus-circle"></i>
                      
                        Agregar
                    </PrimaryButton>
                </div>
            </div>
            
            <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                <!-- <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">IMAGEN</th>
                            <th class="px-2 py-2">DESCRIPCION</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="img, i in images.data" :key="img.id">
                            <td class="border border-gray-400 px-2 py-2">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                               
                                <a href="#imagen1" >
                                <img v-bind:src="'/storage/'+ img.imagen">
                                </a>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">{{ img.descripcion }}</td>

                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton 
                                    @click="$event=>openModal(2,
                                                              img.imagen,
                                                              img.descripcion,
                                                             img.id)">
                                    <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="$event=>deleteClientes(img.id,img.descripcion)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>

                            <td class="border border-gray-400 px-2 py-2">
                                <PrimaryButton 
                                    @click="$event=>zoomImagen(img.imagen)">
                                    <i class="fa-solid fa-eye"></i>
                                </PrimaryButton>
                            </td>

                        </tr>
                    </tbody>
                </table> -->

                <div class="row" >
            <div class="col-12 col-md-4 p-5 mt-3" v-for="img2, i in images.data" :key="img2.id">
               <center> 
                 <a href="#">
                    <img style="max-width: 350px; max-height: 320px; vertical-align:middle;" 
                         v-bind:src="'/storage/'+ img2.imagen" 
                         class="rounded-circle img-fluid border">
                </a>
                <h5 class="text-center mt-3 mb-3">{{ img2.descripcion }} </h5>
                <PrimaryButton 
                    @click="$event=>zoomImagen(img2.imagen)">
                    <i class="fa-solid fa-eye"></i>
                </PrimaryButton>
                <!-- <WarningButton 
                                    @click="$event=>openModal(2,
                                                              img2.imagen,
                                                              img2.descripcion,
                                                             img2.id)">
                                    <i class="fa-solid fa-edit"></i>
                                </WarningButton> -->
                <DangerButton @click="$event=>deleteImagen(img2.id,img2.descripcion)">
                        <i class="fa-solid fa-trash"></i>
                </DangerButton>
              </center>
                             
            </div>
           
        </div>
            </div>

            <!-- <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                <VueTailwindPagination
                   :current="images.currentPage" :total="images.total"
                   :per-page="images.perPage"
                   @page-changed="$event => onPageClick($event)"
                   >

                </VueTailwindPagination>
            </div> -->

            <article class="light-box" id="imagen1">
            <!-- <a href="" class="next"><i class="fa-solid fa-arrow-left"></i> </a> -->
            <!-- <img v-bind:src="'/storage/'+imglink" alt="imagen" id="imgview"> -->
            <img src="" alt="imagen" id="imgview">
            <!-- <a href="" class="next"><i class="fa-solid fa-arrow-right"></i> </a> -->
            <p  class="close" @click="cerrarImg()">X</p>
            </article>


          <hr>
        



        </div>


        

        <Modal :show="modal" @close="closeModal">
            <form method="post" enctype="multipart/form-data" novalidate>
            <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
            <div class="p-3">
                <TextInput id="client_id"
                           v-model="form.client_id" type="hidden" class="mt-1 block w-3/4"
                           placeholder="Client id">
                </TextInput>

                <TextInput id="id"
                           v-model="form.id" type="hidden" class="mt-1 block w-3/4"
                           placeholder="imagen id">
                </TextInput>

                <InputLabel for="imagen" value="Imagen:"></InputLabel>
                <TextInput id="imagen" 
                           @input="form.imagen=$event.target.files[0]"
                            type="file" class="mt-1 block w-3/4"
                           placeholder="Imagen">
                </TextInput>
                <InputError :message="form.errors.imagen" class="mt-2"></InputError>
            </div>
            

            <div class="p-3">
                <InputLabel for="descripcion" value="Descripcion:"></InputLabel>
                <TextInput id="descripcion"
                           v-model="form.descripcion" type="text" class="mt-1 block w-3/4"
                           placeholder="Descripcion">
                </TextInput>
                <InputError :message="form.errors.descripcion" class="mt-2"></InputError>
            </div>

            

            

            <div class="p-3 mt-6">
                <PrimaryButton :disabled="form.processing" @click="save">
                    <i class="fa-solid fa-save"></i>Save
                </PrimaryButton>
            </div>

            <div class="p-3 mt-6 flex justify-end">
                 <SecondaryButton class="ml-3" :disabled="form.processing"
                 @click="closeModal">
                    Cancel
                 </SecondaryButton>
            </div>
        </form>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.light-box{
    position:fixed ;
    top: 0;
    left: 0;
    background: rgba(0,0,0,.5);
    transition: transform .3s ease-in-out;
    width: 100%;
    height: 100vh;
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
    transform: scale(0);
}

.light-box img{
    /*width: 100vw;
    height: 100vh;*/
}
.light-box:target{
    transform: scale(1);
}
.close{
    display: block;
    position: absolute;
    top: 40px;
    right: 40px;
    background: #851919;
    color: #fff;
    text-decoration: none;
    height: 40px;
    width: 40px;
    text-align: center;
    line-height: 40px;
    border-radius: 50%;

}

</style>
