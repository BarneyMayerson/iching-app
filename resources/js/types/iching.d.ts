export interface Line {
  position: number;
  meaning: string;
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
}

export interface Reading {
  id: number;
  question: string;
  date: string;
  time: string;
  binary: string;
  hexagram: Hexagram;
  relative_date: string;
  coin_results: number[];
}
