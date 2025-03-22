export interface Datatrace {
    x: number[];
    y: number[];
    type: string;
    name: string;
    mode: string;
    marker?: {
        size: number;
    };
}

export function addDataTrace(
    x: number[],
    y: number[],
    type: string,
    name: string,
    marker: { size: number },
): Datatrace {
    return {
        x: x,
        y: y,
        type: type,
        name: name,
        mode: "markers",
        marker: marker,
    };
}
