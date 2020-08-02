import { storiesOf } from '@storybook/vue';
import { withKnobs, boolean, select } from '@storybook/addon-knobs/vue';

import FlyoutPanel from '../../themes/app/src/js/components/FlyoutPanel.vue';

const flyoutDirections = { left: 'left', right: 'right' };

storiesOf('Components/Flyout Panel', module)
  .addDecorator(withKnobs)
  .add('with toggle', () => ({
    components: { FlyoutPanel },
    template: `<div>
      <button class="btn btn-primary" @click="menuOpen = true">Open {{direction}}</button>
      <flyout-panel
        :slideFrom="direction"
        :open="menuOpen"
        @close="menuOpen = !menuOpen"
      >
        <h1>Flyout Panel</h1>
        <p>Slides in from the {{direction}}</p>
      </flyout-panel>
    </div>`,
    props: {
      menuOpen: {
        default: boolean('Open', false),
      },
      direction: {
        default: select('Direction', flyoutDirections, 'right'),
      }
    },
  }), { info: {} });

