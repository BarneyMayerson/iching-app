import HexagramLine from '@/components/IChing/HexagramLine.vue';
import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

describe('HexagramLine', () => {
    it('renders correctly', () => {
        const wrapper = mount(HexagramLine, {
            props: { value: 9 }, // Старый Ян
        });
        expect(wrapper.exists()).toBe(true);
    });

    it('renders a solid line for Yang (7 or 9)', () => {
        const wrapper = mount(HexagramLine, {
            props: { value: 7 },
        });
        // у сплошной линии будет один основной блок
        expect(wrapper.find('[data-test="solid-line"]').exists()).toBe(true);
        expect(wrapper.find('[data-test="broken-line"]').exists()).toBe(false);
    });
});
