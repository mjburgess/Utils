var ThinkEngine = ThinkEngine || {};

ThinkEngine.Math = function(M) {

    function newArray(size) {
        return Array.apply(Array, new Array(size));
    }

    M.differential = function(a, b, h) {
        return (b - a) / h;
    };

    M.novelty = function(exp, eWeight, obs, oWeight) {
        return Math.round(100000 * (exp * oWeight - obs * eWeight) / (exp * exp)) / 1000;
    };

    M.doublingTime = function(ratePercent, unitTime, unitOut) {
        unitOut = unitOut || 60;
        unitOut = Math.log(2) / Math.log(1 + Math.abs(ratePercent / 100)) * unitTime / unitOut;

        return unitOut > 10 ? Math.round(unitOut) : Math.round(unitOut * 100) / 100;
    };

    M.Matrix = {
        create: function(rows, cols) {
            return newArray(rows).map(function() {
                return newArray(cols);
            });
        },

        populate: function(m, fn) {
            return m.map(function(row, r) {
                return row.map(function(col, c) {
                    return fn(r, row, c, col);
                });
            });
        },

        generate: function(rows, cols, fn) {
            return M.Matrix.populate(M.Matrix.create(rows, cols), fn);
        }
    };

    M.Stats = {
        rand: function(range) {
            range = range || 0.5;

            return Math.floor(Math.random() * 21 - 10) / (2 * range * 10);
        },

        gaussianDensity: function(x, mean, stdev) {
            stdev *= 2 * stdev;

            var coeff = Math.sqrt(Math.PI * stdev);
            var diff = (x - mean) * (x - mean);

            return coeff * Math.exp(diff / stdev);
        },

        gaussianRand: function(mean, stdev, frequency) {
            var x = M.Stats.mean(newArray(frequency || M.Stats.rand).map(M.Stats.rand));
            return x * stdev + mean;
        },

        degradedRand: function(mean, stdev, degredation) {
            return (mean - degredation) + M.Stats.rand() * (stdev + 1.5 * degredation);
        },

        mean: function(ys) {
            return M.Stats.sum(ys) / ys.length;
        },

        sum: function(ys) {
            return ys.reduce(function(sum, y) {
                return sum + y;
            });
        },

        meansq: function(ys) {
            return M.Stats.sum(ys.map(function(y) {
                return y * y;
            })) / ys.length;
        },

        statistics: function(ys, xstep) {
            var s = {
                N: ys.length,
                Sxy: 0,
                Sx: 0,
                Sy: 0,
                Sxx: 0,
                Syy: 0,
                Mx: 0,
                Mxx: 0,
                My: 0,
                Myy: 0,
                Dx: 0,
                Dy: 0
            };

            for ( var i = 0, x = 0; i < s.N; i++, x += xstep) {
                var y = ys[i];

                s.Sxy += x * y;
                s.Sx += x;
                s.Sy += y;
                s.Sxx += x * x;
                s.Syy += y * y;
            }

            s.Mx = s.Sx / s.N;
            s.My = s.Sy / s.N;
            s.Mxx = s.Sxx / s.N;
            s.Myy = s.Syy / s.N;

            s.Dx = s.Mxx - s.Mx * s.Mx;
            s.Dy = s.Myy - s.My * s.My;

            return s;
        },

        slope: function(s) {
            return (s.N * s.Sxy - s.Sx * s.Sy) / (s.N * s.Sxx - s.Sx * s.Sx);
        },

        stdev: function(ys) {
            return Math.sqrt(M.Stats.meansq(ys) - Math.pow(M.Stats.mean(ys), 2));
        }

    };
    return M;
}(ThinkEngine.Math || {});

ThinkEngine.Math = function(M) {
    function genPoint(p) {
        return function(r, _, c, _) {
            return p.startTime + r + c + ThinkEngine.Math.Stats.rand();
        };
    }

    function bin(data, bins) {
        bins = bins || 100;

        var binSize = (data[i] - data[data.length]) / bins;
        var binned = [];

        for ( var i = 0; i < data.length; i++) {
            binned[Math.round(data[i]) / binSize]++;
        }

        return binned;
    }

    M.SEM = {
        generate: function(p) {
            apData = M.Matrix.generate(p.totalSamples, p.samplesPerTime, genPoint(p));
            smData = M.Matrix.generate(p.totalSamples, p.samplesPerTime, genPoint(p));
            dfData = M.SEM.differences(apData, smData);
            dfData.map(bin);
            console.log(dfData);
        },

        differences: function(apData, smData) {
            var dfData = [];
            for ( var i = 0; i < apData.length; i++) {
                dfData[i] = apData[i] - smData[i];
            }

            return dfData;
        }
    };

    return M;
}(ThinkEngine.Math || {});

p = {
    startTime: 0,
    sampleTime: 60 * 5, // 5 m
    samplesPerTime: 100,
    totalSamples: 150,

    expected: 3,
    eVariation: 5,
    eDegredation: 5,

    observed: 3, // Requests
    oVariation: 50, // 100% = 1.5 (ie. 3 +- 1.5)
    oDegredation: 33
// 33% ~= 1 Request/t
};
ThinkEngine.Math.SEM.generate(p);

db.statistics.mapReduce(
    function() {
        try {
            emit("v0", {
                keys: Object.keys(this.GTPCV0)
            });
        } catch (e) {
        }

        try {
            emit("v1", {
                keys: Object.keys(this.GTPCV1)
            });
        } catch (e) {
        }

        try {
            emit("v2", {
                keys: Object.keys(this.GTPCV2)
            });
        } catch (e) {
        }
    },
    function(k, vs) {
        var out = [];
    
        vs.forEach(function(v) {
            out = out.concat(v.keys)
        });
    
        return {
            keys: Array.unique(out)
        };
    },
    { finalize: function (k, v) {
            return {keys: Array.unique(v.keys)};
        },
        out: "gtpv"
    }
);
        
