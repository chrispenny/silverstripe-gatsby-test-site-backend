import { storiesOf } from '@storybook/vue';
import VueTinySlider from 'vue-tiny-slider';

// Default styles - remove this if necesary to display the styles of your project accurately
import 'tiny-slider/src/tiny-slider.scss';

storiesOf('Components/Tiny Slider', module)
  .add('Standard', () => ({
    components: {
      'tiny-slider': VueTinySlider,
    },
    template: `<div style="max-width: 1200px; margin: 0 auto;">
      <tiny-slider :mouse-drag="true" :loop="false" items="1" gutter="20">
        <img src="http://placekitten.com/1200/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1201/500" alt="A kitten" />
        <img src="http://placekitten.com/1202/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1203/500" alt="A kitten" />
        <img src="http://placekitten.com/1204/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1205/500" alt="A kitten" />
      </tiny-slider>
    </div>`,
  }))

  .add('Without controls', () => ({
    components: {
      'tiny-slider': VueTinySlider,
    },
    template: `<div style="max-width: 1200px; margin: 0 auto;">
      <p style="margin-top: 20px;">Drag to change the slide.</p>
      <tiny-slider :mouse-drag="true" :loop="false" items="1" gutter="20" :controls="false">
        <img src="http://placekitten.com/1200/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1201/500" alt="A kitten" />
        <img src="http://placekitten.com/1202/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1203/500" alt="A kitten" />
        <img src="http://placekitten.com/1204/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1205/500" alt="A kitten" />
      </tiny-slider>
    </div>`,
  }))

  .add('With nav', () => ({
    components: {
      'tiny-slider': VueTinySlider,
    },
    template: `<div style="max-width: 1200px; margin: 0 auto;">
      <p style="margin-top: 20px;">Use the buttons to go direct to a slide.</p>
      <tiny-slider :mouse-drag="true" :loop="false" items="1" gutter="20" :nav="true" :controls="false">
        <img src="http://placekitten.com/1200/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1201/500" alt="A kitten" />
        <img src="http://placekitten.com/1202/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1203/500" alt="A kitten" />
        <img src="http://placekitten.com/1204/500" alt="A kitten" />
        <img src="http://placekitten.com/g/1205/500" alt="A kitten" />
      </tiny-slider>
    </div>`,
  }));
