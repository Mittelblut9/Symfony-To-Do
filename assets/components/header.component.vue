<template>
  <section class="header">
    <picture>
      <source :srcset="require(`@/img/${m_header_bg}`)" media="(max-width: 357px)">

      <img :src="require(`@/img/${header_bg}`)" alt="" class="absolute top-0 left-0 width-100" loading="lazy" decoding="async"/>
    </picture>
  </section>
</template>

<script>
export default {
  name: "header.component",
  data() {
    return {
      header_bg: "",
      m_header_bg: "",
      header: {
        light: "bg-desktop-light.jpg",
        dark: "bg-desktop-dark.jpg",
        m_light: "bg-mobile-light.jpg",
        m_dark: "bg-mobile-dark.jpg",
      }
    }
  },
  beforeMount() {
    const prefersDarkMode = window.matchMedia("(prefers-color-scheme:dark)").matches

    this.header_bg = prefersDarkMode ? this.header.dark : this.header.light;
    this.m_header_bg = prefersDarkMode ? this.header.m_dark : this.header.m_light;
  }
}
</script>

<style scoped>
@import url('@/styles/header/header.css');
@import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');
</style>
