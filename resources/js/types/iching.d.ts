export interface Line {
  position: number;
  meaning: string;
}

export interface Trigram {
  number: number;
  names: string[];
  symbol: string;
  binary: string;
  attribute: string;
  elements: string[];
  origins: {
    name: {
      chinese: string;
      pinyin: string;
    };
    image: {
      chinese: string;
      pinyin: string;
    };
  };
  family: string;
}

export interface Hexagram {
  binary: string;
  number: number;
  character: string;
  names: string[];
  origins: {
    chinese: string;
    pinyin: string;
  };
  judgment: string;
  image?: string;
  lines: Line[];
  top_trigram: Trigram;
  bottom_trigram: Trigram;
}

export interface Reading {
  uuid: string;
  question: string;
  date: string;
  time: string;
  binary: string;
  hexagram: Hexagram;
  secondary_binary?: string;
  secondary_hexagram?: Hexagram;
  relative_date: string;
  coin_results: number[];
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface PaginationMeta {
  current_page: number;
  from: number;
  last_page: number;
  links: PaginationLink[];
  path: string;
  per_page: number;
  to: number;
  total: number;
}
