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
        <div class="m-2 ml-3" v-if="this.productVersion.id">
          <!-- <h4>Images</h4> -->
          <h5>Product Version: {{this.productVersion.id}}</h5>
        </div>
      </div>
      <div class="d-flex justify-content-end">
        <div class="m-1 mr-3" v-if="this.productVersion.id">
          <!-- <h5>Selected Product Version: {{this.productVersion.id}}</h5> -->
        </div>
        <div>
          <b-button class="mr-2" variant="info" @click="read()">Reload</b-button>
        </div>
        <div>
          <b-button
            class="mr-2"
            variant="primary"
            v-if="this.productVersion.id"
            @click="update(productVersion.id, selected)"
          >Assign selected images</b-button>
        </div>
        <div>
          <b-button variant="primary" v-b-modal.uploadimage>new image</b-button>
        </div>
      </div>

      <b-modal id="uploadimage" title="Upload Images" hide-footer>
        <form method="post" action="/uploadimages" role="form" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrf" />

          <b-form-file
            multiple
            required
            name="image[]"
            accept="image/*"
            v-model="file"
            :state="Boolean(file)"
            placeholder="Choose a file or drop it here..."
            drop-placeholder="Drop file here..."
          ></b-form-file>
          <div class="mt-3">Selected file: {{ file ? file.name : '' }}</div>

          <b-button type="submit" variant="primary">Submit</b-button>
        </form>
      </b-modal>
    </div>

    <b-table
      ref="selectableTable"
      @row-selected="onRowSelected"
      :selectable="this.selectable"
      :busy="mute"
      :items="images"
      :fields="fields"
      select-mode="multi"
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
    <!-- {{ selected }} -->
  </div>
</template> 

<script>
function Image({ id, url, product_version }) {
  this.id = id;
  this.thumbnailUrl = "/images/small/" + url;
  if (product_version) {
    this.product = product_version.product.name;
    this.productId = product_version.product.id;
    this.productVersionId = product_version.id;
    if (product_version.color) {
      this.color = product_version.color.name;
    } else {
      this.color = "not assinged";
    }
  } else {
    this.product = "not assinged";
    this.productId = "not assinged";
    this.productVersionId = "not assinged";
    this.color = "not assinged";
  }
}

export default {
  props: ["productVersion"],

  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),

      perPage: 10,
      currentPage: 1,
      file: "",
      fields: [
        "url",
        {
          key: "product",
          sortable: true
        },
        "productId",
        "productVersionId",
        {
          key: "color",
          sortable: true
        },
        "delete"
      ],
      selectable: false,
      selected: [],
      images: []
    };
  },
  methods: {
    onRowSelected(items) {
      this.selected = items;
    },
    read() {
      this.images = [];
      this.mute = true;
      window.axios.get("/api/images").then(({ data }) => {
        data.forEach(image => {
          this.images.push(new Image(image));
        });
        this.mute = false;
      });
    },
    update(product_version_id, select) {
      select.forEach(selectdimage => {
        window.axios
          .put(`/api/images/${selectdimage.id}`, { product_version_id })
          .then(() => {
            console.log(product_version_id);
            console.log(
              this.images.find(image => image.id === selectdimage.id)
            );

            this.images.find(
              image => image.id === selectdimage.id
            ).productVersionId = product_version_id;

            this.$emit(
              "toast",
              "success",
              "Assinging",
              "Image: " + product_version_id + " Assinged"
            );

            // this.images = [];
            // this.read();
          });
      });
      // this.read();
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
    },
    productVersion(val) {
      this.$refs.selectableTable.clearSelected();

      // this.images.forEach(image => {
      //   console.log(image.productVersionId);
      //   if (image.productVersionId == productVersion.id) {
      //     this.$refs.selectableTable.selectRow(2);
      //   }
      // });

      if (this.productVersion.id) {
        this.selectable = true;
        console.log("true");
      } else {
        this.selectable = false;
        console.log("false");
      }
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
