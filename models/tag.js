'use strict';
module.exports = (sequelize, DataTypes) => {
  const Tag = sequelize.define('Tag', {
    name: DataTypes.STRING
  }, {
    underscored: true,
  });
  Tag.associate = function(models) {
    // associations can be defined here
  };
  return Tag;
};