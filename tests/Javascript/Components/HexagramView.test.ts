import HexagramLine from '@/components/IChing/HexagramLine.vue';
import HexagramView from '@/components/IChing/HexagramView.vue';
import { Hexagram, Trigram } from '@/types/iching';
import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest'; // Добавили vi

describe('HexagramView.vue', () => {
  // Создаем моковые триграммы, чтобы не дублировать код
  const mockTrigram: Trigram = {
    number: 1,
    names: ['The Creative'],
    symbol: '☰',
    binary: '111',
    attribute: 'Strong',
    elements: ['Heaven'],
    origins: {
      name: {
        chinese: '乾',
        pinyin: 'Qián',
      },
      image: {
        chinese: '天',
        pinyin: 'Tiān',
      },
    },
    family: 'Father',
  };
  const mockHexagram: Hexagram = {
    binary: '111111',
    number: 1,
    character: '䷀',
    names: ['The Creative'],
    origins: {
      chinese: '乾',
      pinyin: 'qián',
    },
    judgment: 'May you find success through perseverance.',
    lines: [], // Пустой массив для тестов отображения карточки достаточно
    top_trigram: mockTrigram,
    bottom_trigram: mockTrigram,
  };

  const createWrapper = (props: any) => {
    return mount(HexagramView, {
      props,
      global: {
        // Мокаем функцию __
        mocks: {
          __: (key: string) => key,
        },
      },
    });
  };

  it('renders 6 lines in the correct vertical order (bottom to top)', () => {
    const coinResults = [6, 7, 7, 7, 7, 9];
    const wrapper = createWrapper({
      coinResults,
      hexagram: mockHexagram,
    });

    const lines = wrapper.findAllComponents(HexagramLine);
    expect(lines).toHaveLength(6);

    // В DOM (сверху вниз): верхняя линия (9), нижняя линия (6)
    expect(lines[0].props('value')).toBe(9);
    expect(lines[5].props('value')).toBe(6);
  });

  it('displays hexagram information correctly', () => {
    const wrapper = createWrapper({
      coinResults: [7, 7, 7, 7, 7, 7],
      hexagram: mockHexagram,
    });

    expect(wrapper.text()).toContain('The Creative');
    expect(wrapper.text()).toContain('乾');
    expect(wrapper.text()).toContain('Hexagram 1');
  });
});
