<template>
  <div>
    <b-row class="mb-4">
      <div class="w-100" v-if="this.mute == false">
        <div class="w-100" v-for="image in images" v-bind:key="image.id">
          <b-col :class="image.classObject">
            <b-img-lazy
              class="w-100"
              fluid-grow
              :src="image.url"
              blank-height="1000"
              blank-width="1000"
            ></b-img-lazy>
          </b-col>
        </div>
      </div>
      <div
        v-else
        style="height: 500px"
        class="w-100 d-flex align-items-center justify-content-center"
      >
        <div>
          <b-spinner label="Loading..."></b-spinner>
        </div>
      </div>

      <div class="w-100"></div>
    </b-row>
    <b-row>
      <b-col class="d-flex justify-content-start">
        <div
          style="width: 25%"
          v-for="image in images"
          v-bind:key="image.id"
          :class="image.classThumbnailContainer"
        >
          <div @click="selectImage(image.id)">
            <b-img-lazy
              :class="image.classThumbnailObject"
              thumbnail
              fluid-grow
              :src="image.thumbnailUrl"
            ></b-img-lazy>
          </div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  // de data/eigenschp van dit compontent
  props: ["images", "mute"],
  //   dingen terug sturen
  methods: {
    selectImage(val) {
      this.$emit("selectImage", val);
    }
  }
};
</script>