<template>
  <div class="x-input" :class="{'x-input-error': !!error}">
      <label class="x-label" :class="labelClass" v-if="!!label">{{ label }}
        <slot name="label"></slot>
        <el-tooltip :content="tooltipContent"
                    effect="dark"
                    placement="top-end"
                    v-if="!!tooltipContent">
          <i class="fa fa-info-circle"></i>
        </el-tooltip>
      </label>
    <slot></slot>
    <small class="x-label-error" v-text="textError"></small>
  </div>
</template>

<style lang="scss">
.x-input {
  display: flex;
  flex-direction: column;
  padding-bottom: 8px;

  .x-label {
    margin-bottom: 2px;
  }

  .x-label-error {
    display: none;
    color: red;
  }

  &.x-input-error {
    .x-label {
      color: red;
    }

    .el-input__inner {
      border-color: red;
    }

    .x-label-error {
      display: block;
    }
  }

}
</style>

<script>
export default {
  name: 'XInput',
  props: {
    label: {
      type: String,
      default: null,
    },
    value: {
      default: ''
    },
    error: {
      default: null,
    },
    tooltipContent: {
      type: String,
      default: null,
    },
    labelClass: {
      type: String,
      default: null,
    }
  },
  data() {
    return {}
  },
  computed: {
    textError() {
      if (!_.isNull(this.error)) {
        return this.error[0];
      }
      return '';
    }
  },
  methods: {}
}
</script>
