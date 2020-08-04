'use strict';
module.exports = (sequelize, DataTypes) => {
  const Role = sequelize.define('Role', {
    name: DataTypes.STRING
  }, {
    underscored: true,
  });
  Role.associate = function(models) {
    // associations can be defined here
  };
  return Role;
};