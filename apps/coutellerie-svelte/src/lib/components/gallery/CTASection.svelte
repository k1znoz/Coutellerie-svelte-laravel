<script lang="ts">
  export let title: string;
  export let text: string;
  export let buttonText: string;
  export let buttonLink: string;
  export let backgroundColor: string = 'var(--light-grey)';
</script>

<section class="cta-section" style="background-color: {backgroundColor}">
  <div class="container text-center">
    <h2>{title}</h2>
    <p class="mb-4">{text}</p>
    <div>
      <a href={buttonLink} class="btn btn-primary">{buttonText}</a>
    </div>
  </div>
</section>

<style>
  .cta-section {
    padding: 4rem 2rem;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .text-center {
    text-align: center;
  }
  
  .mb-4 {
    margin-bottom: 1rem;
  }
</style>
