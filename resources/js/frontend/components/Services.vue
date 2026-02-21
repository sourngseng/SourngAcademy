<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="lg:text-center">
        <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Campus Life</h2>
        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
          Everything you need to succeed
        </p>
        <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
          We provide comprehensive support services to ensure you have the best academic and personal experience.
        </p>
      </div>

      <div class="mt-10">
        <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
          <div v-for="service in services" :key="service.name" class="relative">
            <dt>
              <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                <i :class="service.icon" class="text-xl"></i>
              </div>
              <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ service.name }}</p>
            </dt>
            <dd class="mt-2 ml-16 text-base text-gray-500">
              {{ service.description }}
            </dd>
          </div>
        </dl>
      </div>
    </div>
  </section>
</template>

<script setup>
const services = [
  {
    name: 'Modern Library',
    description: 'Access to thousands of books, journals, and digital resources in our state-of-the-art library.',
    icon: 'pi pi-book',
  },
  {
    name: 'Career Center',
    description: 'Expert guidance on internships, job placements, and career planning for your future.',
    icon: 'pi pi-briefcase',
  },
  {
    name: 'Student Support',
    description: 'Counseling, health services, and academic advising to support your well-being.',
    icon: 'pi pi-heart',
  },
  {
    name: 'Sports & Recreation',
    description: 'Stay active with our gym, sports fields, and various student clubs and teams.',
    icon: 'pi pi-star', // Using a generic star if specific sports icon isn't available in standard set
  },
];
</script>
