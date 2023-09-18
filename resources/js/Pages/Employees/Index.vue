<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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

const props = defineProps({
    employees:{type:Object},
    departments:{type:Object}
});
const form = useForm({
    name:'',
    email:'',
    clave:'',
    phone:'',
    user_id:'',
    tipo:'',
   // departments_id:''
});

const formPage = useForm({}); //para la paginacion
const onPageClick = (event)=>{
    formPage.get(route('employees.index',{page:event}));
}

const openModal = (op,name,email,clave,phone,tipo,employee)=>{
    modal.value = true;
    nextTick(()=>nameInput.value.focus());
    operation.value=op;
    id.value=employee;
    if(op==1){
        title.value='Crear empleado';
    }
    else{
        title.value='Actualizar empleado';
        form.name=name;
        form.email=email;
        form.clave=clave;
        form.phone=phone;
        //form.user_id=user_id;
        form.tipo=tipo;
        //form.departments_id=department;

    }
}

const closeModal=()=>{
    modal.value=false;
    form.reset();
}

const save=()=>{
    if(operation.value==1){
        form.post(route('employees.store'),{
            onSuccess: ()=>{ok('Empleado creado')}
        });
    }
    else{
        form.put(route('employees.update',id.value),{
            onSuccess: ()=>{ok('Empleado actualizado')}
        });
    }
}

const ok=(msj)=>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'success'})
}

const deleteEmployee = (id,name) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'Are you sure delete '+name+' ?',
        icon:'question', showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check"></i> Yes, delete',
        cancelButtonText:'<i class="fa-solid fa-ban"></i> Cancel'
    }).then((result)=>{
        if(result.isConfirmed){
            /*form.delete(route('employees.destroy',id,{
                onSuccess:()=>{ ok('Employee deleted') }
            }));*/
            form.delete(route('employees.destroy',id),{
            onSuccess: ()=>{ok('Empleado eliminado')}
        });
        }
    });
}
</script>

<template>
    <Head title="Employees" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Empleados</h2>
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
                            <th class="px-2 py-2">NAME</th>
                            <th class="px-2 py-2">EMAIL</th>
                            <th class="px-2 py-2">CLAVE</th>
                            <th class="px-2 py-2">TELEFONO</th>
                            <th class="px-2 py-2">TIPO</th>
                            <!-- <th class="px-2 py-2">DEPARTMENT</th> -->
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="emp, i in employees.data" :key="emp.id">
                            <td class="border border-gray-400 px-2 py-2">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ emp.name }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ emp.email }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ emp.clave }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ emp.phone }}</td>

                            <td class="border border-gray-400 px-2 py-2" v-if="emp.tipo==1"> {{ "Administrador" }}</td>
                            <td class="border border-gray-400 px-2 py-2" v-else> {{ "Peluquero" }}</td>

                            <!-- <td class="border border-gray-400 px-2 py-2">{{ emp.department }}</td> -->

                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton 
                                    @click="$event=>openModal(2,
                                                              emp.name,
                                                              emp.email,
                                                              emp.clave,
                                                              emp.phone,
                                                              emp.tipo,
                                                             // emp.departments_id,
                                                              emp.id)">
                                    <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="$event=>deleteEmployee(emp.id,emp.name)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                <VueTailwindPagination
                   :current="employees.currentPage" :total="employees.total"
                   :per-page="employees.perPage"
                   @page-changed="$event => onPageClick($event)"
                   >

                </VueTailwindPagination>
            </div>

        </div>

        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
            <div class="p-3">
                <InputLabel for="name" value="Name:"></InputLabel>
                <TextInput id="name"
                           v-model="form.name" type="text" class="mt-1 block w-3/4"
                           placeholder="Name">
                </TextInput>
                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>

            <div class="p-3">
                <InputLabel for="email" value="Email:"></InputLabel>
                <TextInput id="email"
                           v-model="form.email" type="email" class="mt-1 block w-3/4"
                           placeholder="Email">
                </TextInput>
                <InputError :message="form.errors.email" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="clave" value="Clave:"></InputLabel>
                <TextInput id="clave"
                           v-model="form.clave" type="text" class="mt-1 block w-3/4"
                           placeholder="Clave">
                </TextInput>
                <InputError :message="form.errors.clave" class="mt-2"></InputError>
            </div>

            <div class="p-3">
                <InputLabel for="phone" value="Telefono:"></InputLabel>
                <TextInput id="phone"
                           v-model="form.phone" type="text" class="mt-1 block w-3/4"
                           placeholder="Telefono">
                </TextInput>
                <InputError :message="form.errors.phone" class="mt-2"></InputError>
            </div>

            <!-- <div class="p-3">
                <InputLabel for="tipo" value="Tipo:"></InputLabel>
                <TextInput id="tipo"
                           v-model="form.tipo" type="text" class="mt-1 block w-3/4"
                           placeholder="Tipo">
                </TextInput>
                <InputError :message="form.errors.tipo" class="mt-2"></InputError>
            </div> -->

            <div class="p-3">
                <InputLabel for="tipo" value="Tipo de empleado:"></InputLabel>
                <!-- <SelectInput id="tipo" :options="departments"
                           v-model="form.tipo" class="mt-1 block w-3/4"
                           >
                </SelectInput>
                <InputError :message="form.errors.tipo" class="mt-2"></InputError> -->

             <select id="tipo" v-model="form.tipo" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-3/4">
                <option disabled value="">Seleccione un tipo</option> 
                <option value="1">Administrador</option>
                 <option value="2">Peluquero</option>
             </select>
             <InputError :message="form.errors.tipo" class="mt-2"></InputError>
            </div>

            <!-- <div class="p-3">
                <InputLabel for="departments_id" value="Departamento:"></InputLabel>
                <SelectInput id="departments_id" :options="departments"
                           v-model="form.departments_id" class="mt-1 block w-3/4"
                           >
                </SelectInput>
                <InputError :message="form.errors.departments_id" class="mt-2"></InputError>
            </div> -->

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
