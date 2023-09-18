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
import { Head,Link,useForm } from '@inertiajs/vue3';
import { nextTick,ref} from 'vue';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

const nameInput = ref(null);
const modal = ref(false);
const title = ref('');
const operation = ref(1);
const id = ref('');

const props = defineProps({
   /// employees:{type:Object},
    clients:{type:Object},
   // departments:{type:Object}
});
//declaracion de las variables del formulario
const form = useForm({
    name:'',
    apellidos:'',
    telefono:'',
    observacion:'',
});

const formPage = useForm({}); //para la paginacion
const onPageClick = (event)=>{
    formPage.get(route('clients.index',{page:event}));
}
//Abre el modal y carga los dats si se va actualizar
const openModal = (op,name,apellidos,telefono,observacion,cliente)=>{
    modal.value = true;
    nextTick(()=>nameInput.value.focus());
    operation.value=op;
    id.value=cliente;
    if(op==1){
        title.value='Crear Cliente';
    }
    else{
        title.value='Actualizar Cliente';
        form.name=name;
        form.apellidos=apellidos;
        form.telefono=telefono;
        //form.user_id=user_id;
        form.observacion=observacion;
       // form.departments_id=department;

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
        form.post(route('clients.store'),{
            onSuccess: ()=>{ok('Cliente creado')}
        });
    }
    else{
        form.put(route('clients.update',id.value),{
            onSuccess: ()=>{ok('Cliente actualizado ')}
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
const deleteClientes = (id,name) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'Esta seguro de eliminar a '+name+' ?',
        icon:'question', showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check"></i> Yes, delete',
        cancelButtonText:'<i class="fa-solid fa-ban"></i> Cancel'
    }).then((result)=>{
        if(result.isConfirmed){
            /*form.delete(route('employees.destroy',id,{
                onSuccess:()=>{ ok('Employee deleted') }
            }));*/
            form.delete(route('clients.destroy',id),{
            onSuccess: ()=>{ok('Cliente eliminado')}
        });
        }
    });
}
</script>

<template>
    <Head title="Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
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
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">NOMBRE</th>
                            <th class="px-2 py-2">APELLIDOS</th>
                            <th class="px-2 py-2">TELEFONO</th>
                            <th class="px-2 py-2">OBSERVACIÓN</th>
                            <th class="px-2 py-2">PERSONAL</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cli, i in clients.data" :key="cli.id">
                            <td class="border border-gray-400 px-2 py-2">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ cli.name }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ cli.apellidos }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ cli.telefono }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ cli.observacion }}</td>

                            <!-- <td class="border border-gray-400 px-2 py-2" v-if="cli.tipo==1"> {{ "Administrador" }}</td>
                            <td class="border border-gray-400 px-2 py-2" v-else> {{ "Peluquero" }}</td> -->

                            <td class="border border-gray-400 px-2 py-2">{{ cli.personal }}</td>

                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton 
                                    @click="$event=>openModal(2,
                                                              cli.name,
                                                              cli.apellidos,
                                                              cli.telefono,
                                                              cli.observacion,
                                                             // cli.departments_id,
                                                              cli.id)">
                                    <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="$event=>deleteClientes(cli.id,cli.name)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">                             
                                <Link :href="route('clients.show',cli.id)"
                                      :class="'px-4 py-2 bg-green-400 text-white border rounded-md font-semibold text-xs'">
                                 <i class="fa-solid fa-image"></i>
                                  
                                </Link>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                <VueTailwindPagination
                   :current="clients.currentPage" :total="clients.total"
                   :per-page="clients.perPage"
                   @page-changed="$event => onPageClick($event)"
                   >

                </VueTailwindPagination>
            </div>

        </div>

        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
            <div class="p-3">
                <InputLabel for="name" value="Nombre:"></InputLabel>
                <TextInput id="name"
                           v-model="form.name" type="text" class="mt-1 block w-3/4"
                           placeholder="Nombre">
                </TextInput>
                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>

            <div class="p-3">
                <InputLabel for="apellidos" value="Apellidos:"></InputLabel>
                <TextInput id="apellidos"
                           v-model="form.apellidos" type="text" class="mt-1 block w-3/4"
                           placeholder="Apellidos">
                </TextInput>
                <InputError :message="form.errors.apellidos" class="mt-2"></InputError>
            </div>

            <div class="p-3">
                <InputLabel for="telefono" value="Telefono:"></InputLabel>
                <TextInput id="telefono"
                           v-model="form.telefono" type="text" class="mt-1 block w-3/4"
                           placeholder="Telefono">
                </TextInput>
                <InputError :message="form.errors.telefono" class="mt-2"></InputError>
            </div>


            <div class="p-3">
                <InputLabel for="observacion" value="Observacion:"></InputLabel>
                <TextInput id="observacion"
                           v-model="form.observacion" type="text" class="mt-1 block w-3/4"
                           placeholder="Observacion">
                </TextInput>
             
             <InputError :message="form.errors.observacion" class="mt-2"></InputError>
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

        </Modal>
    </AuthenticatedLayout>
</template>
