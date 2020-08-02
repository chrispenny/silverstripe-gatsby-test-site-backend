import { configure } from '@storybook/vue';
import { addDecorator } from '@storybook/vue'
import { withInfo } from 'storybook-addon-vue-info'

// Enable vue info decorator
addDecorator(withInfo)

const req = require.context('./stories', true, /.stories.js$/);

require('./index.stories.js');
function loadStories() {
  req.keys().forEach(filename => req(filename));
}

configure(loadStories, module);
