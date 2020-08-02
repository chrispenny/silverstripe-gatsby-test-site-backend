import { storiesOf } from '@storybook/vue';
import { BadgerAccordion, BadgerAccordionItem } from 'vue-badger-accordion'

storiesOf('Components/Badger Accordion', module)
  .add('Standard', () => ({
    components: { BadgerAccordion, BadgerAccordionItem },
    template: `<div>
      <badger-accordion>
          <badger-accordion-item>
              <template slot="header">First Accordion Header</template>
              <template slot="content">First Accordion Content</template>
          </badger-accordion-item>

          <badger-accordion-item>
              <template slot="header">Second Accordion Header</template>
              <template slot="content">Second Accordion Content</template>
          </badger-accordion-item>
      </badger-accordion>
    </div>`,
  }), { info: {} })

  .add('Without icons', () => ({
    components: { BadgerAccordion, BadgerAccordionItem },
    template: `<div>
      <badger-accordion :icons="false">
          <badger-accordion-item>
              <template slot="header">First Accordion Header</template>
              <template slot="content">First Accordion Content</template>
          </badger-accordion-item>

          <badger-accordion-item>
              <template slot="header">Second Accordion Header</template>
              <template slot="content">Second Accordion Content</template>
          </badger-accordion-item>
      </badger-accordion>
    </div>`,
  }), { info: {} })

  .add('With custom icons', () => ({
    components: { BadgerAccordion, BadgerAccordionItem },
    template: `<div>
      <badger-accordion :icons="customIcons">
          <badger-accordion-item>
              <template slot="header">First Accordion Header</template>
              <template slot="content">First Accordion Content</template>
          </badger-accordion-item>

          <badger-accordion-item>
              <template slot="header">Second Accordion Header</template>
              <template slot="content">Second Accordion Content</template>
          </badger-accordion-item>
      </badger-accordion>
    </div>`,
    data() {
      return {
        customIcons: {
          opened: '<button class="btn">&#x25B2;</button>',
          closed:  '<button class="btn">&#x25BC;</button>',
        },
      };
    },
  }), { info: {} })

  .add('With collapse all', () => ({
    components: { BadgerAccordion, BadgerAccordionItem },
    template: `<div>
      <div>
        <button @click="openAll">Open all</button>
        <button @click="closeAll">Close all</button>
      </div>
      <badger-accordion ref="myAccordion">
          <badger-accordion-item>
              <template slot="header">First Accordion Header</template>
              <template slot="content">First Accordion Content</template>
          </badger-accordion-item>

          <badger-accordion-item>
              <template slot="header">Second Accordion Header</template>
              <template slot="content">Second Accordion Content</template>
          </badger-accordion-item>
      </badger-accordion>
    </div>`,
    methods: {
      openAll() {
        this.$refs.myAccordion.openAll();
      },
      closeAll() {
        this.$refs.myAccordion.closeAll();
      },
    },
  }), { info: {} });

