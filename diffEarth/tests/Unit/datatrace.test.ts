import { addDataTrace, Datatrace } from "../../resources/ts/datatrace";

describe("addDataTrace", () => {
    it("should return a Datatrace with given values", () => {
        const test: Datatrace = addDataTrace(
            [1, 2, 3],
            [4, 5, 6],
            "scatter",
            "testTrace",
            { size: 10 },
        );

        expect(test).toEqual({
            x: [1, 2, 3],
            y: [4, 5, 6],
            type: "scatter",
            name: "testTrace",
            mode: "markers",
            marker: { size: 10 },
        });
    });

    it("should return a datatrace with empty arrays", () => {
        const test: Datatrace = addDataTrace([], [], "scatter", "testTrace", {
            size: 10,
        });

        expect(test).toEqual({
            x: [],
            y: [],
            type: "scatter",
            name: "testTrace",
            mode: "markers",
            marker: { size: 10 },
        });
    });

    it("should allow negative values", () => {
        const test: Datatrace = addDataTrace(
            [-1, -2, -3],
            [-4, -5, -6],
            "scatter",
            "testTrace",
            { size: 10 },
        );

        expect(test).toEqual({
            x: [-1, -2, -3],
            y: [-4, -5, -6],
            type: "scatter",
            name: "testTrace",
            mode: "markers",
            marker: { size: 10 },
        });
    });
});
