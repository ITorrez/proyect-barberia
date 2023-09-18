<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {ref} from 'vue';
import {Bar, Pie,PolarArea} from 'vue-chartjs';/*Tipos de grafica  */
import {Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement} from 'chart.js';

ChartJS.register(Title,Tooltip,Legend,BarElement,CategoryScale,LinearScale,ArcElement);
const props = defineProps({
    data:{type:Object}
});

const departments = ref([]);
const employees = ref([]);
const chartData = ref([]);
const charOptions = ref([]);
const colors = ref([]);

const random = () =>{
    return Math.floor(Math.random()*256);
}

props.data.map((row)=>(
    departments.value.push(row.name),
    employees.value.push(row.count),
    colors.value.push("rgb("+random()+","+random()+","+random()+")")
))

charOptions.value= { responsive:true}
chartData.value = {
    lebels:departments.value,
    datasets:[
        {label:'Employees', data:employees.value, backgroundColor:colors}
    ]
}

</script>

<template>
    <Head title="Grafica" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Grafica</h2>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Pie :data="chartData" :options="charOptions"></Pie>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>