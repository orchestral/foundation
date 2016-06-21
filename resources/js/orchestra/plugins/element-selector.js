class ElementSelector {
  constructor(value) {
    this.value = value
  }

  isClass() {
    return this.value.lastIndexOf('.', 0) === 0
  }

  toString() {
    if (this.isClass()) {
      return this.value
    }

    return `#${this.value}`
  }
}

export default ElementSelector
