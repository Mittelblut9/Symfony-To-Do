<script setup>
import { RouterView } from 'vue-router'
import Header from '@/components/header.component.vue';
</script>

<template>
  <Header />
  <RouterView />
</template>

<style>
@import '@/styles/app.css';
@import 'https://www.blackdayz.de/css/blackcss';
</style>
