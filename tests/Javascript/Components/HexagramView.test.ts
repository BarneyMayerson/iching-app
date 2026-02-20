import HexagramLine from '@/components/IChing/HexagramLine.vue';
import HexagramView from '@/components/IChing/HexagramView.vue';
import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

describe('HexagramView.vue', () => {
  const mockHexagram = {
    number: 1,
    name: 'The Creative',
    chinese_name: '乾',
    pinyin_name: 'qián',
    character: '䷀',
  };

  it('renders 6 lines in the correct vertical order (bottom to top)', () => {
    // 6 — нижняя (1-я), 9 — верхняя (6-я)
    const coinResults = [6, 7, 7, 7, 7, 9];

    const wrapper = mount(HexagramView, {
      props: {
        coinResults,
        hexagram: mockHexagram,
      },
    });

    const lines = wrapper.findAllComponents(HexagramLine);

    expect(lines).toHaveLength(6);

    // Проверяем, что ПЕРВЫЙ отрендеренный компонент в DOM
    // соответствует ПОСЛЕДНЕМУ элементу массива (верхняя линия)
    // И наоборот: ПОСЛЕДНИЙ в DOM — это ПЕРВЫЙ в массиве (база)
    expect(lines[0].props('value')).toBe(9); // Верх
    expect(lines[5].props('value')).toBe(6); // Низ (начало массива)
  });

  it('displays hexagram information correctly', () => {
    const wrapper = mount(HexagramView, {
      props: {
        coinResults: [7, 7, 7, 7, 7, 7],
        hexagram: mockHexagram,
      },
    });

    expect(wrapper.text()).toContain('The Creative');
    expect(wrapper.text()).toContain('乾');
  });
});
