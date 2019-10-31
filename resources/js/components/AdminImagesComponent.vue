<template>
  <div>
    <div class="d-flex justify-content-between mb-4">
      <div class="d-flex justify-content-start">
        <div>
          <b-pagination
            class="mb-0"
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="my-table"
          ></b-pagination>
        </div>
        <div class="m-1 ml-3">
          <h4>Images</h4>
        </div>
      </div>

      <b-button variant="primary" v-b-modal.uploadimage>new image</b-button>
      <b-modal id="uploadimage" title="Upload Images">
        <form method="post" action="/uploadimages" role="form" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrf" />
          <input type="file" accept="image/*" name="image[]" multiple required />
          <b-button type="submit" variant="primary">Submit</b-button>
        </form>
      </b-modal>
    </div>

    <b-table
      :busy="mute"
      :items="images"
      :fields="fields"
      striped
      hover
      outline
      :per-page="perPage"
      :current-page="currentPage"
      responsive="sm"
    >
      <template v-slot:cell(url)="image">
        <b-img-lazy
          thumbnail
          :src="image.item.thumbnailUrl"
          width="100"
          fluid
          alt="Responsive image"
        ></b-img-lazy>
      </template>

      <template v-slot:cell(delete)="row">
        <b-button size="sm" variant="danger" @click="del(row.item.id)" class="mr-2">Delete</b-button>
      </template>

      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
    </b-table>
  </div>
</template> 

<script>
function Image({ id, url, product_version }) {
  this.id = id;
  this.thumbnailUrl = "/images/small/" + url;
  if (product_version) {
    this.product = product_version.product.name;
    this.color = product_version.color.name;
  } else {
    this.product = "not assinged";
    this.color = "not assinged";
  }
}

export default {
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),

      perPage: 10,
      currentPage: 1,
      fields: [
        {
          key: "url",
          sortable: true
        },
        {
          key: "product",
          sortable: true
        },
        {
          key: "color",
          sortable: true
        },
        "delete"
      ],
      images: []
    };
  },
  methods: {
    read() {
      this.mute = true;
      window.axios.get("/api/images").then(({ data }) => {
        data.forEach(image => {
          this.images.push(new Image(image));
        });
        this.mute = false;
      });
    },
    update(id, name) {
      this.mute = true;
      window.axios.put(`/api/images/${id}`, { name }).then(() => {
        this.images.find(image => image.id === id).name = name;
        this.mute = false;
      });
    },
    del(id) {
      this.mute = true;
      window.axios.delete(`/api/images/${id}`).then(() => {
        let index = this.images.findIndex(image => image.id === id);
        this.images.splice(index, 1);
        this.mute = false;
      });
    }
  },
  watch: {
    mute(val) {
      document.getElementById("mute").className = val ? "on" : "";
    }
  },
  computed: {
    rows() {
      return this.images.length;
    }
  },
  created() {
    this.read();
  }
};
</script>
